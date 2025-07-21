<?php
namespace App\Http\Controllers;

use App\Mail\ProjectInvitationMail;
use App\Models\Invitation;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function creationForm()
    {
        return view('projects.create');
    }

    public function invite(Request $request, Project $project)
{
    if (auth()->user()->role_id <= 3) {
        abort(403);
    }

    $request->validate(['email' => 'required|email']);

    $token = Str::uuid();

    Invitation::create([
        'sender_id' => auth()->id(),
        'email' => $request->email,
        'project_id' => $project->id,
        'status' => 0,
        'token' => $token,
    ]);

    Mail::to($request->email)->send(new ProjectInvitationMail($project, $token));

    return back()->with('success', 'Invitation envoyée à ' . $request->email);
}

public function acceptInvitation($token)
{
    $invitation = Invitation::where('token', $token)->firstOrFail();

    if ($invitation->status != 0) {
        return redirect()->route('projects.index')->with('info', 'Invitation déjà traitée.');
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

        $project = Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Projet créé avec succès.');
    }
}