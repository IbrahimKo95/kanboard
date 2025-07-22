<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Models\User;

class ProjectController extends Controller
{
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
        $project->users()->attach(Auth::id(), ['role' => 'owner']);
        return redirect()->route('home')->with('success', 'Projet créé avec succès.');
    }

    public function kanban(Project $project)
    {
        session(['project_' . $project->id . '_view' => 'kanban']);
        return view('projects.show', compact('project'));
    }

    public function invite(Request $request, Project $project)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($project->users->contains($user)) {
            return back()->withErrors(['email' => 'Cet utilisateur est déjà membre du projet.']);
        }
        // Vérifie qu'il n'y a pas déjà une invitation en attente
        $alreadyInvited = Invitation::where('project_id', $project->id)
            ->where('receiver_id', $user->id)
            ->where('status', 0)
            ->exists();
        if ($alreadyInvited) {
            return back()->withErrors(['email' => 'Une invitation est déjà en attente pour cet utilisateur.']);
        }
        Invitation::create([
            'receiver_id' => $user->id,
            'sender_id' => Auth::id(),
            'project_id' => $project->id,
            'status' => 0, // 0 = en attente
        ]);
        return back()->with('success', 'Invitation envoyée !');
    }
}