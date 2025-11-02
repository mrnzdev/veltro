<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\MatchApplication;
use App\Models\TeamJoinRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share pending notifications with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Get all teams where user is owner or captain
                $managedTeamIds = $user->ownedTeams()->pluck('id')
                    ->merge(
                        $user->teams()
                            ->wherePivot('role', 'captain')
                            ->pluck('teams.id')
                    );

                // Get pending join requests for managed teams
                $pendingJoinRequests = TeamJoinRequest::whereIn('team_id', $managedTeamIds)
                    ->where('status', 'pending')
                    ->with(['user', 'team'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Get pending match applications for match requests from managed teams
                $pendingMatchApplications = MatchApplication::whereHas('matchRequest', function ($query) use ($managedTeamIds) {
                    $query->whereIn('team_id', $managedTeamIds);
                })
                    ->where('status', 'pending')
                    ->with(['applicantTeam', 'matchRequest.team'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Calculate total notifications count
                $totalNotificationsCount = $pendingJoinRequests->count() + $pendingMatchApplications->count();

                $view->with('pendingJoinRequestsCount', $pendingJoinRequests->count());
                $view->with('pendingJoinRequests', $pendingJoinRequests);
                $view->with('pendingMatchApplicationsCount', $pendingMatchApplications->count());
                $view->with('pendingMatchApplications', $pendingMatchApplications);
                $view->with('totalNotificationsCount', $totalNotificationsCount);
            } else {
                $view->with('pendingJoinRequestsCount', 0);
                $view->with('pendingJoinRequests', collect());
                $view->with('pendingMatchApplicationsCount', 0);
                $view->with('pendingMatchApplications', collect());
                $view->with('totalNotificationsCount', 0);
            }
        });
    }
}
