<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
        if (!$match->canRecordResults(Auth::user())) {
            abort(403, 'No tienes permiso para registrar resultados de este partido.');
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

        // If user's team already submitted but opponent hasn't
        if ($hasUserTeamSubmitted && !$match->areResultsConfirmed()) {
            return view('matches.results.pending', compact('match', 'userTeam', 'opponentTeam', 'userTeamSide', 'hasOpponentSubmitted'));
        }

        // Show the recording form
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

        DB::transaction(function () use ($match, $validated, $user, $userTeamSide, $userTeamId) {
            // Store participants
            $participants = $validated['participants'] ?? [];
            foreach ($participants as $userId) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'team_id' => $userTeamId,
                    'user_id' => $userId,
                ]);
            }

            // Store goals
            $goals = $validated['goals'] ?? [];
            foreach ($goals as $goal) {
                MatchGoal::create([
                    'match_id' => $match->id,
                    'team_id' => $userTeamId,
                    'scorer_id' => $goal['scorer_id'],
                    'minute' => $goal['minute'],
                ]);
            }

            // Calculate scores
            $scores = $match->calculateScores();

            // Update match with submission info
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

            // Check if both teams have submitted
            if ($match->hasTeamSubmittedResults() && $match->hasOpponentSubmittedResults()) {
                // Both teams submitted - check if results match
                $teamParticipants = $match->participants()->where('team_id', $match->team_id)->pluck('user_id')->sort()->values()->toArray();
                $opponentParticipants = $match->participants()->where('team_id', $match->opponent_team_id)->pluck('user_id')->sort()->values()->toArray();

                $teamGoals = $match->goals()->where('team_id', $match->team_id)->get()->map(function ($goal) {
                    return ['scorer_id' => $goal->scorer_id, 'minute' => $goal->minute];
                })->sortBy('minute')->values()->toArray();

                $opponentGoals = $match->goals()->where('team_id', $match->opponent_team_id)->get()->map(function ($goal) {
                    return ['scorer_id' => $goal->scorer_id, 'minute' => $goal->minute];
                })->sortBy('minute')->values()->toArray();

                // Simple comparison - if same data, auto-confirm
                $resultsMatch = ($teamParticipants == $opponentParticipants) && 
                               ($teamGoals == $opponentGoals) &&
                               ($match->team_score == $scores['team_score']) &&
                               ($match->opponent_score == $scores['opponent_score']);

                if ($resultsMatch) {
                    $match->update([
                        'result_confirmed_at' => now(),
                        'status' => 'completed',
                    ]);
                }
            }
        });

        if ($match->areResultsConfirmed()) {
            return redirect()->route('matches.results.show', $match)
                ->with('success', '¡Resultados registrados y confirmados automáticamente!');
        }

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
}
