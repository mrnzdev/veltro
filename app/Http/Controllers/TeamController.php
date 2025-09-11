<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $ownedTeams = $user->ownedTeams()->active()->with('members')->get();
        $memberTeams = $user->teams()->active()->with('owner')->get();
        $allTeams = Team::active()->with(['owner', 'members'])->paginate(12);

        return view('teams.index', compact('ownedTeams', 'memberTeams', 'allTeams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'description' => 'nullable|string|max:1000',
            'max_members' => 'required|integer|min:2|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            $team = Team::create([
                ...$validated,
                'owner_id' => Auth::id(),
            ]);

            // Add the owner as a member with 'owner' role
            $team->members()->attach(Auth::id(), [
                'role' => 'owner',
                'joined_at' => now(),
            ]);
        });

        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team): View
    {
        $team->load(['owner', 'members']);
        $user = Auth::user();
        
        $isOwner = $team->isOwnedBy($user);
        $isMember = $team->hasMember($user);
        $isCaptain = $team->isCaptain($user);

        return view('teams.show', compact('team', 'isOwner', 'isMember', 'isCaptain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team): View
    {
        $this->authorize('update', $team);
        
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'description' => 'nullable|string|max:1000',
            'max_members' => 'required|integer|min:2|max:50',
            'is_active' => 'boolean',
        ]);

        $team->update($validated);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully!');
    }

    /**
     * Join a team.
     */
    public function join(Team $team): RedirectResponse
    {
        $user = Auth::user();

        if ($team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'You are already a member of this team.');
        }

        if (!$team->hasSpaceForMembers()) {
            return redirect()->back()
                ->with('error', 'This team is full.');
        }

        $team->members()->attach($user->id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'You have successfully joined the team!');
    }

    /**
     * Leave a team.
     */
    public function leave(Team $team): RedirectResponse
    {
        $user = Auth::user();

        if (!$team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'You are not a member of this team.');
        }

        if ($team->isOwnedBy($user)) {
            return redirect()->back()
                ->with('error', 'Team owners cannot leave their own team. Transfer ownership or delete the team instead.');
        }

        $team->members()->detach($user->id);

        return redirect()->back()
            ->with('success', 'You have successfully left the team.');
    }

    /**
     * Promote a member to captain.
     */
    public function promote(Team $team, User $user): RedirectResponse
    {
        $this->authorize('manage', $team);

        if (!$team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'User is not a member of this team.');
        }

        $team->members()->updateExistingPivot($user->id, [
            'role' => 'captain',
        ]);

        return redirect()->back()
            ->with('success', 'Member promoted to captain successfully!');
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember(Team $team, User $user): RedirectResponse
    {
        $this->authorize('manage', $team);

        if (!$team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'User is not a member of this team.');
        }

        if ($team->isOwnedBy($user)) {
            return redirect()->back()
                ->with('error', 'Cannot remove the team owner.');
        }

        $team->members()->detach($user->id);

        return redirect()->back()
            ->with('success', 'Member removed from team successfully!');
    }
}
