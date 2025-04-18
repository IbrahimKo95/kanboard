<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;


class TaskController extends Controller
{

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'priority_id' => 'nullable|exists:priority,id',
            'column_id' => 'nullable|exists:columns,id',
        ]);
        $validated['project_id'] = $project->id;
        $validated['user_id'] = auth()->id();

        $task = Task::create($validated);

        return back()->with('success', 'Task created successfully.');
    }

    public function delete(Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'priority_id' => 'nullable|exists:priority,id',
            'column_id' => 'nullable|exists:columns,id',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task updated successfully.');
    }

    public function assignUser(Request $request, Task $task)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $task->assignedUsers()->attach($validated['user_id']);

        return back()->with('success', 'User assigned to task successfully.');
    }

    public function unassignUser(Request $request, Task $task)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $task->assignedUsers()->detach($validated['user_id']);

        return back()->with('success', 'User unassigned from task successfully.');
    }

    public function list(Project $project)
    {
        $tasks = $project->tasks;
        $users = $project->users;
        return view('tasks.list', compact('tasks', 'project', 'users'));

    }
}
