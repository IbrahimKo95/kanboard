<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Project;
use Illuminate\Http\Request;


class ColumnController extends Controller
{

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);
        if(isset($request->finished_column)) {
            $validated['finished_column'] = true;
        } else {
            $validated['finished_column'] = false;
        }
        $validated['project_id'] = $project->id;

        Column::create($validated);

        return back()->with('success', 'Column created successfully.');
    }


}
