<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
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

        return redirect()->route('projects.index')->with('success', 'Projet créé avec succès.');
    }

    public function kanban(Project $project)
    {
        session(['project_' . $project->id . '_view' => 'kanban']);
        return view('projects.show', compact('project'));
    }
}
