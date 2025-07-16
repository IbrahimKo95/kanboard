<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Liste des tâches</h2>
        <button data-modal-target="modalCreateTask" data-modal-toggle="modalCreateTask" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nouvelle tâche
        </button>
    </div>

    <div class="space-y-4">
        @foreach($project->tasks as $task)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" data-modal-target="modalEditTask{{ $task->id }}" data-modal-toggle="modalEditTask{{ $task->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $task->title }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-600 font-medium">{{ $task->column->name }}</span>
                            @php
                                $priorityColors = [
                                    'Urgente' => 'bg-red-100 text-red-600',
                                    'Importante' => 'bg-orange-100 text-orange-600',
                                    'Moyenne' => 'bg-yellow-100 text-yellow-600',
                                    'Faible' => 'bg-green-100 text-green-600'
                                ];
                                $priorityLabel = $task->priority?->name ?? 'Aucune';
                                $priorityClass = $priorityColors[$priorityLabel] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full {{ $priorityClass }} font-medium">
                                {{ $priorityLabel }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-3">{{ $task->description }}</p>
                        
                        @if($task->due_date)
                            <div class="flex items-center text-sm {{\Carbon\Carbon::parse($task->due_date)->isPast() ? "text-red-500" : "text-gray-500"}} mb-3">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M4 11h16M4 19h16M4 15h16"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                @if( \Carbon\Carbon::parse($task->due_date)->isPast())
                                    <span class="ml-1">(En retard)</span>
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                @foreach($task->assignedUsers as $user)
                                    <div class="w-8 h-8 rounded-full border-2 border-white text-white text-xs flex items-center justify-center font-bold {{ $user->avatar()['color'] }}" title="{{ $user->fullName() }}">
                                        {{ $user->avatar()['initials'] }}
                                    </div>
                                @endforeach
                            </div>
                            <span class="text-xs text-gray-500">Créé le {{ $task->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal d'édition de tâche -->
            <div id="modalEditTask{{$task->id}}" tabindex="-1" class="hidden fixed inset-0 bg-black/30 z-50 flex justify-center items-center">
                <div class="relative w-full max-w-lg bg-white rounded-lg shadow p-6">
                    <button data-modal-hide="modalEditTask{{$task->id}}" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900">
                        ✕
                    </button>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Modifier une tâche</h3>
                    <form method="POST" action="{{ route('tasks.update', [$task]) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="title{{$task->id}}" class="block mb-1 font-medium text-gray-900">Titre</label>
                            <input value="{{$task->title}}" type="text" name="title" id="title{{$task->id}}" class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div class="mb-4">
                            <label for="description{{$task->id}}" class="block mb-1 font-medium text-gray-900">Description</label>
                            <textarea name="description" id="description{{$task->id}}" class="w-full border rounded px-3 py-2">{{$task->description}}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="due_date{{$task->id}}" class="block mb-1 font-medium text-gray-900">Date d'échéance</label>
                            <input id="due_date{{$task->id}}" class="w-full border rounded px-3 py-2" value="{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '' }}" type="date" name="due_date">
                        </div>

                        <div class="mb-4">
                            <label for="priority{{$task->id}}" class="block mb-1 font-medium text-gray-900">Priorité</label>
                            <select name="priority_id" id="priority{{$task->id}}" class="w-full border rounded px-3 py-2">
                                <option value="">-- Choisir --</option>
                                @foreach(\App\Models\Priority::all() as $priority)
                                    <option value="{{ $priority->id }}" {{ $task->priority_id == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label>Assigner à</label>
                            <div class="flex flex-col gap-2 h-32 overflow-y-scroll mt-2">
                                @foreach($project->users as $user)
                                    <div class="flex items-center gap-2 bg-gray-100 px-4 py-3 rounded-md">
                                        <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}" id="user_{{ $user->id }}" {{ $task->assignedUsers->contains($user) ? 'checked' : '' }}>
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
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div> 