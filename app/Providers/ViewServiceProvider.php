<?php

declare(strict_types=1);

namespace App\Providers;

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
        // Share pending join requests with all views
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

                $view->with('pendingJoinRequestsCount', $pendingJoinRequests->count());
                $view->with('pendingJoinRequests', $pendingJoinRequests);
            } else {
                $view->with('pendingJoinRequestsCount', 0);
                $view->with('pendingJoinRequests', collect());
            }
        });
    }
}
