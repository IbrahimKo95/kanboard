@extends('layouts.app')

@section('title', 'Liste des projets')

@section('content')
<h1>Liste des projets</h1>

@if($projects->isEmpty())
<p>Aucun projet trouvé.</p>
@else
<ul>
    @foreach ($projects as $project)
    <li class="p-4 border rounded shadow">
        <h2 class="text-xl font-semibold">
            <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 hover:underline">
                {{ $project->name }}
            </a>
        </h2>
        <p class="text-gray-600">{{ $project->description }}</p>
    </li>
    @endforeach
</ul>
@endif
<a href="{{ route('projects.create') }}">Créer un projet</a>
@endsection