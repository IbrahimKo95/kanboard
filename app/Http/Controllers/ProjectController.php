<?php
namespace App\Http\Controllers;

use App\Mail\ProjectInvitationMail;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProjectController extends Controller
{

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return back()->with('success', 'Projet mis à jour avec succès.');
    }

    public function index()
    {
        $projects = Auth::user()->projects;
        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        if (!Auth::user()->projects->contains($project)) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }
        // Définir la vue par défaut si aucune session n'est définie
        if (!session()->has('project_' . $project->id . '_view')) {
            session(['project_' . $project->id . '_view' => 'kanban']);
        }
        return view('projects.show', compact('project'));
    }

    public function creationForm()
    {
        return view('projects.create');
    }

    public function invite(Request $request, Project $project)
{
    $user = auth()->user();
    $pivot = $user->projects()->where('project_id', $project->id)->first()?->pivot;
    // Seuls les rôles <= 3 (owner/admin/member) peuvent inviter
    if (!$pivot || $pivot->role > 3) {
        abort(403);
    }

    $request->validate(['email' => 'required|email']);

    $token = Str::uuid();

    $invitation = Invitation::create([
        'sender_id' => $user->id,
        'email' => $request->email,
        'project_id' => $project->id,
        'status' => 0,
        'token' => $token,
    ]);

    Mail::to($request->email)->send(new ProjectInvitationMail($invitation));

    return back()->with('success', 'Invitation envoyée à ' . $request->email);
}

public function acceptInvitation($token)
{
    $invitation = Invitation::where('token', $token)->firstOrFail();

    if ($invitation->status != 0) {
        return redirect()->route('home')->with('info', 'Invitation déjà traitée.');
    }

    $user = auth()->user();
    $invitation->update([
        'receiver_id' => $user->id,
        'status' => 1,
    ]);

    $invitation->project->users()->attach($user->id);

    return redirect()->route('projects.show', $invitation->project)->with('success', 'Vous avez rejoint le projet !');
}

    public function showUsers(Project $project)
    {
        $users = $project->users;
        return view('projects.users', compact('project', 'users'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date'
        ]);
        if (!isset($validated['description'])) {
            $validated['description'] = '';
        }

        $project = Project::create($validated);

        $project->users()->attach(auth()->id(), ['role' => 3]);

        $project->columns()->create([
            'name' => 'À faire',
            'color' => '#006aff',
            'finished_column' => false
        ]);
        $project->columns()->create([
            'name' => 'En cours',
            'color' => '#ff6600',
            'finished_column' => false
        ]);
        $project->columns()->create([
            'name' => 'Terminé',
            'color' => '#ff2a00',
            'finished_column' => true
        ]);

        return redirect()->route('home')->with('success', 'Projet créé avec succès.');
    }

    public function kanban(Project $project)
    {
        session(['project_' . $project->id . '_view' => 'kanban']);
        return view('projects.show', compact('project'));
    }
}
