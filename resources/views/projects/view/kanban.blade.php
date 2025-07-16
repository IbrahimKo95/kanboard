<div class="overflow-x-auto w-full">
    <div class="flex gap-6 p-6 w-max">
        @foreach($project->columns as $column)
            <div class="w-80 flex-none">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full" style="background-color: {{$column->color}}"></span>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $column->name }}</h2>
                        <span class="text-xs text-gray-500 bg-gray-200 px-2 rounded-full">{{ count($column->tasks) }}</span>
                    </div>
                    <button data-modal-target="modalCreateTask{{$column->id}}" data-modal-toggle="modalCreateTask{{$column->id}}" class="text-xl font-semibold text-gray-500 hover:text-gray-700">+</button>
                </div>

                <div class="task-list space-y-4" data-column-id="{{ $column->id }}">
                    @foreach($column->tasks as $task)
                        <div data-modal-target="modalEditTask{{ $task->id }}" data-modal-toggle="modalEditTask{{ $task->id }}" class="task bg-white p-4 rounded-xl border {{ !\Carbon\Carbon::parse($task->due_date)->isPast() || !isset($task->due_date) ? "border-gray-200" : "border-red-400"}} shadow-sm cursor-move" draggable="true" data-task-id="{{ $task->id }}">
                        <div class="flex justify-between items-center mb-2">
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-600 font-medium">Développement</span>
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

                            <p class="font-semibold text-gray-800">{{ $task->title }}</p>
                            <p class="text-sm text-gray-600">{{ $task->description }}</p>

                            @if($task->due_date)
                                <div class="mt-2 flex items-center text-sm {{\Carbon\Carbon::parse($task->due_date)->isPast() ? "text-red-500" : "text-gray-500"}}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3M16 7V3M4 11h16M4 19h16M4 15h16"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                    @if( \Carbon\Carbon::parse($task->due_date)->isPast())
                                        <span class="ml-1">(En retard)</span>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center justify-between mt-4 text-gray-500 text-sm">
                                <div class="flex gap-3">

                                </div>
                                <div class="flex -space-x-2">
                                    @foreach($task->assignedUsers as $user)
                                        <div class="w-8 h-8 rounded-full border-2 border-white text-white text-xs flex items-center justify-center font-bold {{ $user->avatar()['color'] }}">
                                            {{ $user->avatar()['initials'] }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
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
                                        <label for="title{{$column->id}}" class="block mb-1 font-medium text-gray-900">Titre</label>
                                        <input value="{{$task->title}}" type="text" name="title" id="title{{$column->id}}" class="w-full border rounded px-3 py-2" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="description{{$column->id}}" class="block mb-1 font-medium text-gray-900">Description</label>
                                        <textarea name="description" id="description{{$column->id}}" class="w-full border rounded px-3 py-2">{{$task->description}}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="due_date{{$column->id}}" class="block mb-1 font-medium text-gray-900">Date d'échéance</label>
                                        <input id="due_date{{$column->id}}" class="w-full border rounded px-3 py-2" value="{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '' }}" type="date" name="due_date">
                                    </div>

                                    <div class="mb-4">
                                        <label for="priority{{$column->id}}" class="block mb-1 font-medium text-gray-900">Priorité</label>
                                        <select name="priority_id" id="priority{{$column->id}}" class="w-full border rounded px-3 py-2">
                                            <option value="">-- Choisir --</option>
                                            @foreach(\App\Models\Priority::all() as $priority)
                                                <option selected="{{$task->priority_id == $priority->id}}" value="{{ $priority->id }}">{{ $priority->name }}</option>
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
            <div id="modalCreateTask{{$column->id}}" tabindex="-1" class="hidden fixed inset-0 bg-black/30 z-50 flex justify-center items-center">
                <div class="relative w-full max-w-lg bg-white rounded-lg shadow p-6">
                    <button data-modal-hide="modalCreateTask{{$column->id}}" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900">
                        ✕
                    </button>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Créer une tâche</h3>
                    <form method="POST" action="{{ route('tasks.store', ['project' => $project->id, 'column' => $column->id]) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="title{{$column->id}}" class="block mb-1 font-medium text-gray-900">Titre</label>
                            <input type="text" name="title" id="title{{$column->id}}" class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div class="mb-4">
                            <label for="description{{$column->id}}" class="block mb-1 font-medium text-gray-900">Description</label>
                            <textarea name="description" id="description{{$column->id}}" class="w-full border rounded px-3 py-2"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="due_date{{$column->id}}" class="block mb-1 font-medium text-gray-900">Date d'échéance</label>
                            <input id="due_date{{$column->id}}" class="w-full border rounded px-3 py-2" type="date" name="due_date">
                        </div>

                        <div class="mb-4">
                            <label for="priority{{$column->id}}" class="block mb-1 font-medium text-gray-900">Priorité</label>
                            <select name="priority_id" id="priority{{$column->id}}" class="w-full border rounded px-3 py-2">
                                <option value="">-- Choisir --</option>
                                @foreach(\App\Models\Priority::all() as $priority)
                                    <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label>Assigner à</label>
                            <div class="flex flex-col gap-2 h-32 overflow-y-scroll mt-2">
                                @foreach($project->users as $user)
                                    <div class="flex items-center gap-2 bg-gray-100 px-4 py-3 rounded-md">
                                        <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}" id="user_{{ $user->id }}">
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
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

