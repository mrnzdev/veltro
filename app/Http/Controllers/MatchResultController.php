<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DisputeMatchResultRequest;
use App\Http\Requests\StoreMatchResultRequest;
use App\Models\MatchGoal;
use App\Models\MatchParticipant;
use App\Models\TeamMatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MatchResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form to record match results.
     */
    public function show(TeamMatch $match): View
    {
        if (!$match->canViewResults(Auth::user())) {
            abort(403, 'No tienes permiso para ver los resultados de este partido.');
        }

        $match->load(['team.members', 'opponentTeam.members', 'participants.user', 'goals.scorer']);

        $userTeamSide = $match->getUserTeamSide(Auth::user());
        $userTeam = $userTeamSide === 'team' ? $match->team : $match->opponentTeam;
        $opponentTeam = $userTeamSide === 'team' ? $match->opponentTeam : $match->team;

        // Check if user's team has already submitted
        $hasUserTeamSubmitted = ($userTeamSide === 'team' && $match->hasTeamSubmittedResults()) ||
                                ($userTeamSide === 'opponent' && $match->hasOpponentSubmittedResults());

        // Check if opponent has submitted
        $hasOpponentSubmitted = ($userTeamSide === 'team' && $match->hasOpponentSubmittedResults()) ||
                                ($userTeamSide === 'opponent' && $match->hasTeamSubmittedResults());

        // If both submitted and results are confirmed, redirect to show view
        if ($match->areResultsConfirmed()) {
            return view('matches.results.show', compact('match', 'userTeam', 'opponentTeam', 'userTeamSide'));
        }

        // If ANYONE has submitted (either team), show the pending/review page
        // This prevents the second team from trying to record again (which causes duplicate errors)
        if ($hasUserTeamSubmitted || $hasOpponentSubmitted) {
            return view('matches.results.pending', compact('match', 'userTeam', 'opponentTeam', 'userTeamSide', 'hasOpponentSubmitted', 'hasUserTeamSubmitted'));
        }

        // Only show recording form if NO ONE has submitted yet
        return view('matches.results.record', compact('match', 'userTeam', 'opponentTeam', 'userTeamSide'));
    }

    /**
     * Store match results.
     */
    public function store(StoreMatchResultRequest $request, TeamMatch $match): RedirectResponse
    {
        $validated = $request->validated();
        $user = Auth::user();
        $userTeamSide = $match->getUserTeamSide($user);
        $userTeamId = $userTeamSide === 'team' ? $match->team_id : $match->opponent_team_id;
        $opponentTeamId = $userTeamSide === 'team' ? $match->opponent_team_id : $match->team_id;

        DB::transaction(function () use ($match, $validated, $user, $userTeamSide, $userTeamId, $opponentTeamId) {
            // Store user team participants
            $participants = $validated['participants'] ?? [];
            foreach ($participants as $userId) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'team_id' => $userTeamId,
                    'user_id' => $userId,
                ]);
            }

            // Store opponent team participants
            $opponentParticipants = $validated['opponent_participants'] ?? [];
            foreach ($opponentParticipants as $userId) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'team_id' => $opponentTeamId,
                    'user_id' => $userId,
                ]);
            }

            // Store user team goals
            $goals = $validated['goals'] ?? [];
            foreach ($goals as $goal) {
                MatchGoal::create([
                    'match_id' => $match->id,
                    'team_id' => $userTeamId,
                    'scorer_id' => $goal['scorer_id'] ?? null,
                    'minute' => $goal['minute'] ?? null,
                ]);
            }

            // Store opponent team goals
            $opponentGoals = $validated['opponent_goals'] ?? [];
            foreach ($opponentGoals as $goal) {
                MatchGoal::create([
                    'match_id' => $match->id,
                    'team_id' => $opponentTeamId,
                    'scorer_id' => $goal['scorer_id'] ?? null,
                    'minute' => $goal['minute'] ?? null,
                ]);
            }

            // Calculate scores from all goals
            $scores = $match->calculateScores();

            // Update match with submission info and scores
            if ($userTeamSide === 'team') {
                $match->update([
                    'team_result_submitted_at' => now(),
                    'team_result_submitted_by' => $user->id,
                    'team_score' => $scores['team_score'],
                    'opponent_score' => $scores['opponent_score'],
                ]);
            } else {
                $match->update([
                    'opponent_result_submitted_at' => now(),
                    'opponent_result_submitted_by' => $user->id,
                    'team_score' => $scores['team_score'],
                    'opponent_score' => $scores['opponent_score'],
                ]);
            }
        });

        return redirect()->route('matches.results.show', $match)
            ->with('success', '¡Resultados registrados! Esperando confirmación del equipo contrario.');
    }

    /**
     * Confirm results submitted by the other team.
     */
    public function confirm(Request $request, TeamMatch $match): RedirectResponse
    {
        if (!$match->canRecordResults(Auth::user())) {
            return redirect()->back()
                ->with('error', 'No tienes permiso para confirmar estos resultados.');
        }

        if ($match->areResultsConfirmed()) {
            return redirect()->back()
                ->with('error', 'Los resultados ya han sido confirmados.');
        }

        $userTeamSide = $match->getUserTeamSide(Auth::user());

        // Check if opponent has submitted and user's team hasn't
        $canConfirm = false;
        if ($userTeamSide === 'team' && $match->hasOpponentSubmittedResults() && !$match->hasTeamSubmittedResults()) {
            $canConfirm = true;
        } elseif ($userTeamSide === 'opponent' && $match->hasTeamSubmittedResults() && !$match->hasOpponentSubmittedResults()) {
            $canConfirm = true;
        }

        if (!$canConfirm) {
            return redirect()->back()
                ->with('error', 'No puedes confirmar estos resultados en este momento.');
        }

        DB::transaction(function () use ($match, $userTeamSide) {
            // Mark as submitted for the confirming team
            if ($userTeamSide === 'team') {
                $match->update([
                    'team_result_submitted_at' => now(),
                    'team_result_submitted_by' => Auth::id(),
                ]);
            } else {
                $match->update([
                    'opponent_result_submitted_at' => now(),
                    'opponent_result_submitted_by' => Auth::id(),
                ]);
            }

            // Now both have submitted, confirm the match
            $match->update([
                'result_confirmed_at' => now(),
                'status' => 'completed',
            ]);
        });

        return redirect()->route('matches.results.show', $match)
            ->with('success', '¡Resultados confirmados! El partido ha sido completado.');
    }

    /**
     * Dispute results submitted by the other team.
     */
    public function dispute(DisputeMatchResultRequest $request, TeamMatch $match): RedirectResponse
    {
        $validated = $request->validated();
        $userTeamSide = $match->getUserTeamSide(Auth::user());

        DB::transaction(function () use ($match, $validated, $userTeamSide) {
            // Clear all participants and goals to allow re-submission
            $match->participants()->delete();
            $match->goals()->delete();

            // Clear submission timestamps to reset the flow
            $match->update([
                'team_result_submitted_at' => null,
                'team_result_submitted_by' => null,
                'opponent_result_submitted_at' => null,
                'opponent_result_submitted_by' => null,
                'team_score' => null,
                'opponent_score' => null,
            ]);

            // TODO: Optionally log the dispute reason for admin review
            // You could add a match_disputes table or notification system here
        });

        return redirect()->route('matches.results.show', $match)
            ->with('warning', 'Resultados disputados. El equipo contrario deberá volver a registrar los resultados.');
    }

    /**
     * Allow the submitting team to edit their submission before confirmation.
     */
    public function edit(TeamMatch $match): RedirectResponse
    {
        if (!$match->canRecordResults(Auth::user())) {
            return redirect()->back()
                ->with('error', 'No tienes permiso para editar estos resultados.');
        }

        if ($match->areResultsConfirmed()) {
            return redirect()->back()
                ->with('error', 'Los resultados ya han sido confirmados y no pueden editarse.');
        }

        $userTeamSide = $match->getUserTeamSide(Auth::user());

        // Check if user's team has submitted (can only edit your own submission)
        $hasUserTeamSubmitted = ($userTeamSide === 'team' && $match->hasTeamSubmittedResults()) ||
                                ($userTeamSide === 'opponent' && $match->hasOpponentSubmittedResults());

        if (!$hasUserTeamSubmitted) {
            return redirect()->back()
                ->with('error', 'Tu equipo no ha registrado resultados aún.');
        }

        DB::transaction(function () use ($match, $userTeamSide) {
            // Clear all participants and goals to allow re-submission
            $match->participants()->delete();
            $match->goals()->delete();

            // Clear submission timestamps
            $match->update([
                'team_result_submitted_at' => null,
                'team_result_submitted_by' => null,
                'opponent_result_submitted_at' => null,
                'opponent_result_submitted_by' => null,
                'team_score' => null,
                'opponent_score' => null,
            ]);
        });

        return redirect()->route('matches.results.show', $match)
            ->with('success', 'Los resultados han sido eliminados. Puedes registrarlos nuevamente.');
    }
}
