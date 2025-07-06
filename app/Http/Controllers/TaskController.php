<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;


class TaskController extends Controller
{

    public function store(Request $request, Project $project, Column $column)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'priority_id' => 'nullable|exists:priorities,id'
        ]);
        $validated['project_id'] = $project->id;
        $validated['column_id'] = $column->id;
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
            'priority_id' => 'nullable|exists:priorities,id',
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

    public function move(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'column_id' => 'required|exists:columns,id',
            'prev_id' => 'nullable|exists:tasks,id',
            'next_id' => 'nullable|exists:tasks,id',
        ]);

        $task = Task::findOrFail($request->task_id);
        $task->column_id = $request->column_id;

        $prev = $request->prev_id ? Task::find($request->prev_id) : null;
        $next = $request->next_id ? Task::find($request->next_id) : null;

        if ($prev && $next) {
            $task->order = ($prev->order + $next->order) / 2;
        } elseif ($prev) {
            $task->order = $prev->order + 100;
        } elseif ($next) {
            $task->order = $next->order / 2;
        } else {
            $task->order = 1000;
        }

        $task->save();

        $tasks = Task::where('column_id', $request->column_id)->orderBy('order')->get();
        for ($i = 1; $i < count($tasks); $i++) {
            if (abs($tasks[$i]->order - $tasks[$i - 1]->order) < 0.0001) {
                foreach ($tasks as $index => $t) {
                    $t->order = ($index + 1) * 100;
                    $t->save();
                }
                break;
            }
        }
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'column_id' => 'required|exists:columns,id',
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.order' => 'required|numeric'
        ]);

        foreach ($request->tasks as $taskData) {
            Task::where('id', $taskData['id'])->update([
                'order' => $taskData['order'],
                'column_id' => $request->column_id
            ]);
        }

        return response()->json(['success' => true]);
    }

}
