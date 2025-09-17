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
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        $ownedTeams = $user->ownedTeams()->active()->with('members')->get();
        $memberTeams = $user->teams()->active()->with('owner')->get();
        
        // Build query for all teams with search and filters
        $query = Team::active()->with(['owner', 'members']);
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        }
        
        // Filter by team availability
        if ($request->filled('availability')) {
            $availability = $request->get('availability');
            if ($availability === 'open') {
                $query->whereRaw('(SELECT COUNT(*) FROM team_user WHERE team_user.team_id = teams.id) < max_members');
            } elseif ($availability === 'full') {
                $query->whereRaw('(SELECT COUNT(*) FROM team_user WHERE team_user.team_id = teams.id) >= max_members');
            }
        }
        
        // Filter by team size
        if ($request->filled('size')) {
            $size = $request->get('size');
            if ($size === 'small') {
                $query->where('max_members', '<=', 7);
            } elseif ($size === 'medium') {
                $query->whereBetween('max_members', [8, 15]);
            } elseif ($size === 'large') {
                $query->where('max_members', '>', 15);
            }
        }
        
        // Sort options
        $sortBy = $request->get('sort', 'recent');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'members':
                $query->orderByRaw('(SELECT COUNT(*) FROM team_user WHERE team_user.team_id = teams.id) DESC');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // 'recent'
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $allTeams = $query->paginate(12)->withQueryString();

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
            ->with('success', '¡Equipo creado exitosamente!');
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
            ->with('success', '¡Equipo actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', '¡Equipo eliminado exitosamente!');
    }

    /**
     * Join a team.
     */
    public function join(Team $team): RedirectResponse
    {
        $user = Auth::user();

        if ($team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'Ya eres miembro de este equipo.');
        }

        if (!$team->hasSpaceForMembers()) {
            return redirect()->back()
                ->with('error', 'Este equipo está completo.');
        }

        $team->members()->attach($user->id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', '¡Te has unido exitosamente al equipo!');
    }

    /**
     * Leave a team.
     */
    public function leave(Team $team): RedirectResponse
    {
        $user = Auth::user();

        if (!$team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'No eres miembro de este equipo.');
        }

        if ($team->isOwnedBy($user)) {
            return redirect()->back()
                ->with('error', 'Los propietarios de equipos no pueden salir de su propio equipo. Transfiere la propiedad o elimina el equipo en su lugar.');
        }

        $team->members()->detach($user->id);

        return redirect()->back()
            ->with('success', 'Has salido exitosamente del equipo.');
    }

    /**
     * Promote a member to captain.
     */
    public function promote(Team $team, User $user): RedirectResponse
    {
        $this->authorize('manage', $team);

        if (!$team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'El usuario no es miembro de este equipo.');
        }

        $team->members()->updateExistingPivot($user->id, [
            'role' => 'captain',
        ]);

        return redirect()->back()
            ->with('success', '¡Miembro promovido a capitán exitosamente!');
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember(Team $team, User $user): RedirectResponse
    {
        $this->authorize('manage', $team);

        if (!$team->hasMember($user)) {
            return redirect()->back()
                ->with('error', 'El usuario no es miembro de este equipo.');
        }

        if ($team->isOwnedBy($user)) {
            return redirect()->back()
                ->with('error', 'No se puede remover al propietario del equipo.');
        }

        $team->members()->detach($user->id);

        return redirect()->back()
            ->with('success', '¡Miembro removido del equipo exitosamente!');
    }
}
