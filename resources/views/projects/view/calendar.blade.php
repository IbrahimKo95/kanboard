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
                        <div class="text-xs bg-gray-100 p-1 rounded border-l-4 {{ $task->priority?->name === 'Urgente' ? 'border-red-500' : 'border-gray-300' }}">
                            <strong>{{ $task->title }}</strong>
                            <div class="text-gray-500 truncate">{{ $task->description }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
