@extends('layouts.app')

@section('title', 'Liste des projets')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Mes projets</h1>
    <a href="{{ route('projects.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition font-medium">+ Nouveau projet</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6 border border-green-200">
        {{ session('success') }}
    </div>
@endif

@if($projects->isEmpty())
    <div class="text-gray-500 text-center py-12 text-lg">Aucun projet trouvé.</div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($projects as $project)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <h2 class="text-xl font-semibold text-blue-700 mb-2 truncate">
                        <a href="{{ route('projects.show', $project->id) }}" class="hover:underline">
                            {{ $project->name }}
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-4 min-h-[2.5rem]">{{ $project->description ?: 'Aucune description' }}</p>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                    <span><strong>{{ $project->tasks->count() }}</strong> tâches</span>
                    <span><strong>{{ $project->users->count() }}</strong> membres</span>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection