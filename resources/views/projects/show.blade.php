@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">{{ $project->name }}</h1>
    <p class="mb-4 text-gray-700">{{ $project->description }}</p>

    <a href="{{ route('projects.index') }}" class="text-blue-500 hover:underline">Retour à la liste</a>
</div>
@endsection