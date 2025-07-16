@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">

    <h1 class="text-2xl font-bold mb-6">Liste des tâches du projet "{{ $project->name }}"</h1>

    @foreach ($project->tasks as $task)
        <div data-modal-target="modalEditTask{{ $task->id }}" data-modal-toggle="modalEditTask{{ $task->id }}"
            class="bg-white p-6 rounded-xl border shadow hover:shadow-md hover:bg-gray-50 cursor-pointer transition">

            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">{{ $task->title }}</h2>
                <span class="text-sm px-2 py-1 rounded-full {{ 
                    match($task->priority?->name) {
                        'Urgente' => 'bg-red-100 text-red-600',
                        'Importante' => 'bg-orange-100 text-orange-600',
                        'Moyenne' => 'bg-yellow-100 text-yellow-600',
                        'Faible' => 'bg-green-100 text-green-600',
                        default => 'bg-gray-100 text-gray-600'
                    }
                }}">
                    {{ $task->priority->name ?? 'Aucune' }}
                </span>
            </div>

            <p class="text-gray-600 mt-2">{{ $task->description }}</p>

            @if($task->due_date)
                <div class="mt-2 flex items-center text-sm {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-500' : 'text-gray-500' }}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3M16 7V3M4 11h16M4 19h16M4 15h16"></path>
                    </svg>
                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                </div>
            @endif
        </div>

        {{-- Modal d'édition --}}
        <div id="modalEditTask{{ $task->id }}" tabindex="-1"
             class="hidden fixed inset-0 bg-black/30 z-50 flex justify-center items-center">
            <div class="relative w-full max-w-lg bg-white rounded-lg shadow p-6">
                <button data-modal-hide="modalEditTask{{ $task->id }}" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900">✕</button>

                <h3 class="text-lg font-semibold text-gray-900 mb-4">Modifier la tâche</h3>

                <form method="POST" action="{{ route('tasks.update', $task->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block mb-1 font-medium text-gray-900">Titre</label>
                        <input type="text" name="title" value="{{ $task->title }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium text-gray-900">Description</label>
                        <textarea name="description" class="w-full border rounded px-3 py-2">{{ $task->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium text-gray-900">Date d'échéance</label>
                        <input type="date" name="due_date" value="{{ optional($task->due_date)->format('Y-m-d') }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium text-gray-900">Priorité</label>
                        <select name="priority_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Choisir --</option>
                            @foreach(\App\Models\Priority::all() as $priority)
                                <option value="{{ $priority->id }}" {{ $task->priority_id == $priority->id ? 'selected' : '' }}>
                                    {{ $priority->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-900">Assigner à</label>
                        <div class="flex flex-col gap-2 h-32 overflow-y-scroll mt-2">
                            @foreach($task->assignedUsers as $user)
                                <div class="flex items-center gap-2 bg-gray-100 px-4 py-3 rounded-md">
                                    <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}"
                                        id="user_{{ $user->id }}"
                                        {{ $task->assignedUsers->contains($user) ? 'checked' : '' }}>
                                    <div class="w-8 h-8 rounded-full border-2 border-white text-white text-xs flex items-center justify-center font-bold {{ $user->avatar()['color'] }}">
                                        {{ $user->avatar()['initials'] }}
                                    </div>
                                    <div>
                                        <label for="user_{{ $user->id }}" class="text-sm text-gray-700">{{ $user->fullName() }}</label>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
