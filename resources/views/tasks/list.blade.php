@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <ul>
        @foreach ($tasks as $task)
        <li
            class="p-4 border rounded shadow cursor-pointer hover:bg-gray-100"
            onclick="openModal({{ htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') }})">
            <h2 class="text-xl font-semibold">{{ $task->title }}</h2>
            <p class="text-gray-600">{{ $task->description }}</p>
        </li>
        @endforeach
    </ul>

    <a href="{{ route('projects.show', $project) }}" class="text-blue-500 hover:underline">Retour</a>
</div>
@endsection