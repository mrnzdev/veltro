<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ApplyMatchRequestRequest;
use App\Http\Requests\StoreMatchRequestRequest;
use App\Models\MatchApplication;
use App\Models\MatchRequest;
use App\Models\Team;
use App\Models\TeamMatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MatchRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of match requests.
     */
    public function index(Request $request): View
    {
        $query = MatchRequest::with(['team', 'creator'])
            ->open()
            ->where('match_datetime', '>', now())
            ->orderBy('match_datetime', 'asc');

        // Filter by football type
        if ($request->filled('football_type')) {
            $query->forFootballType($request->get('football_type'));
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->inLocation($request->get('location'));
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $endDate = $request->filled('end_date') ? $request->get('end_date') : null;
            $query->inDateRange($request->get('start_date'), $endDate);
        }

        $matchRequests = $query->paginate(12)->withQueryString();

        return view('match-requests.index', compact('matchRequests'));
    }

    /**
     * Show the form for creating a new match request.
     */
    public function create(): View
    {
        $this->authorize('create', MatchRequest::class);

        $user = Auth::user();

        // Get teams where user is owner or captain
        $userTeams = $user->ownedTeams()
            ->active()
            ->get()
            ->merge(
                $user->teams()
                    ->active()
                    ->wherePivot('role', 'captain')
                    ->get()
            )
            ->unique('id');

        return view('match-requests.create', compact('userTeams'));
    }

    /**
     * Store a newly created match request.
     */
    public function store(StoreMatchRequestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        MatchRequest::create([
            'team_id' => $validated['team_id'],
            'match_datetime' => $validated['match_datetime'],
            'location' => $validated['location'],
            'status' => 'open',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('match-requests.index')
            ->with('success', '¡Solicitud de partido creada exitosamente!');
    }

    /**
     * Display the specified match request.
     */
    public function show(MatchRequest $matchRequest): View
    {
        $matchRequest->load(['team', 'creator', 'applications.applicantTeam', 'match.opponentTeam']);

        $user = Auth::user();
        $team = $matchRequest->team;

        $isOwnerOrCaptain = $team->isOwnedBy($user) || $team->isCaptain($user);

        // Get user's teams that can apply
        $userTeamsCanApply = collect();
        if (!$isOwnerOrCaptain && $matchRequest->canAcceptApplications()) {
            $userTeamsCanApply = $user->ownedTeams()
                ->active()
                ->get()
                ->merge(
                    $user->teams()
                        ->active()
                        ->wherePivot('role', 'captain')
                        ->get()
                )
                ->unique('id')
                ->filter(function ($userTeam) use ($matchRequest) {
                    // Filter out teams that already applied
                    return !$matchRequest->applications()
                        ->where('applicant_team_id', $userTeam->id)
                        ->where('status', 'pending')
                        ->exists();
                });
        }

        return view('match-requests.show', compact('matchRequest', 'isOwnerOrCaptain', 'userTeamsCanApply'));
    }

    /**
     * Apply to a match request.
     */
    public function apply(ApplyMatchRequestRequest $request, MatchRequest $matchRequest): RedirectResponse
    {
        if (!$matchRequest->canAcceptApplications()) {
            return redirect()->back()
                ->with('error', 'Esta solicitud de partido ya no acepta aplicaciones.');
        }

        $validated = $request->validated();

        MatchApplication::create([
            'match_request_id' => $matchRequest->id,
            'applicant_team_id' => $validated['team_id'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', '¡Aplicación enviada exitosamente!');
    }

    /**
     * Cancel an application (by the applicant).
     */
    public function cancelApplication(MatchRequest $matchRequest, MatchApplication $application): RedirectResponse
    {
        $user = Auth::user();
        $applicantTeam = $application->applicantTeam;

        // Check if user is owner or captain of the applicant team
        if (!$applicantTeam->isOwnedBy($user) && !$applicantTeam->isCaptain($user)) {
            return redirect()->back()
                ->with('error', 'No tienes permiso para cancelar esta aplicación.');
        }

        if (!$application->isPending()) {
            return redirect()->back()
                ->with('error', 'Esta aplicación ya fue procesada.');
        }

        $application->delete();

        return redirect()->back()
            ->with('success', 'Aplicación cancelada exitosamente.');
    }

    /**
     * Accept an application.
     */
    public function acceptApplication(MatchRequest $matchRequest, MatchApplication $application): RedirectResponse
    {
        $this->authorize('acceptApplication', $matchRequest);

        if ($application->match_request_id !== $matchRequest->id) {
            return redirect()->back()
                ->with('error', 'Aplicación inválida.');
        }

        if (!$application->isPending()) {
            return redirect()->back()
                ->with('error', 'Esta aplicación ya fue procesada.');
        }

        if (!$matchRequest->canAcceptApplications()) {
            return redirect()->back()
                ->with('error', 'Esta solicitud de partido ya no acepta aplicaciones.');
        }

        DB::transaction(function () use ($matchRequest, $application) {
            // Create the match
            TeamMatch::create([
                'match_request_id' => $matchRequest->id,
                'team_id' => $matchRequest->team_id,
                'opponent_team_id' => $application->applicant_team_id,
                'match_datetime' => $matchRequest->match_datetime,
                'location' => $matchRequest->location,
                'status' => 'scheduled',
            ]);

            // Accept the application
            $application->accept();

            // Update match request status
            $matchRequest->update(['status' => 'matched']);

            // Reject all other pending applications
            $matchRequest->applications()
                ->where('id', '!=', $application->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        });

        return redirect()->route('match-requests.show', $matchRequest)
            ->with('success', '¡Aplicación aceptada! El partido ha sido programado.');
    }

    /**
     * Cancel the match request.
     */
    public function cancel(MatchRequest $matchRequest): RedirectResponse
    {
        $this->authorize('delete', $matchRequest);

        if ($matchRequest->status !== 'open') {
            return redirect()->back()
                ->with('error', 'Solo se pueden cancelar solicitudes abiertas.');
        }

        $matchRequest->cancel();

        return redirect()->route('match-requests.my-requests')
            ->with('success', 'Solicitud de partido cancelada exitosamente.');
    }

    /**
     * View user's team match requests.
     */
    public function myRequests(): View
    {
        $user = Auth::user();

        // Get all teams where user is owner or captain
        $userTeamIds = $user->ownedTeams()
            ->pluck('id')
            ->merge(
                $user->teams()
                    ->wherePivot('role', 'captain')
                    ->pluck('teams.id')
            )
            ->unique();

        $matchRequests = MatchRequest::with(['team', 'applications', 'match.opponentTeam'])
            ->whereIn('team_id', $userTeamIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('match-requests.my-requests', compact('matchRequests'));
    }

    /**
     * View user's team applications.
     */
    public function myApplications(): View
    {
        $user = Auth::user();

        // Get all teams where user is owner or captain
        $userTeamIds = $user->ownedTeams()
            ->pluck('id')
            ->merge(
                $user->teams()
                    ->wherePivot('role', 'captain')
                    ->pluck('teams.id')
            )
            ->unique();

        $applications = MatchApplication::with(['matchRequest.team', 'applicantTeam'])
            ->whereIn('applicant_team_id', $userTeamIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('match-requests.my-applications', compact('applications'));
    }
}
