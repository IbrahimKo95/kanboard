<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Calendrier – {{ \Carbon\Carbon::now()->format('F Y') }}</h2>

    @php
        use Carbon\Carbon;

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $days = [];
        $current = $startOfCalendar->copy();

        while ($current <= $endOfCalendar) {
            $days[] = $current->copy();
            $current->addDay();
        }

        $tasksByDate = $project->tasks->groupBy(function($task) {
            return optional($task->due_date)->format('Y-m-d');
        });
    @endphp

    <div class="grid grid-cols-7 gap-2 text-center text-gray-600 font-medium mb-2">
        @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
            <div>{{ $dayName }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-4">
        @foreach($days as $day)
            <div class="border rounded-lg p-2 h-40 flex flex-col {{ $day->isToday() ? 'bg-blue-50 border-blue-400' : 'bg-white' }}">
                <div class="text-sm font-semibold mb-1 text-gray-800">
                    {{ $day->day }}
                </div>

                <div class="space-y-1 overflow-auto text-left">
                    @foreach($tasksByDate[$day->format('Y-m-d')] ?? [] as $task)
                        <div data-modal-target="modalEditTask{{ $task->id }}" data-modal-toggle="modalEditTask{{ $task->id }}" class="text-xs bg-gray-100 p-1 rounded border-l-4 {{ $task->priority?->name === 'Urgente' ? 'border-red-500' : 'border-gray-300' }} cursor-pointer hover:bg-gray-200 transition-colors">
                            <strong>{{ $task->title }}</strong>
                            <div class="text-gray-500 truncate">{{ $task->description }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modals d'édition de tâches -->
    @foreach($project->tasks as $task)
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
