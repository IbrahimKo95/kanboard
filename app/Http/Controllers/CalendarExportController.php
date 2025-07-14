<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Carbon\Carbon;

class CalendarExportController extends Controller
{
    public function export(Project $project)
    {
        $lines = [];

        $lines[] = 'BEGIN:VCALENDAR';
        $lines[] = 'VERSION:2.0';
        $lines[] = 'PRODID:-//Kanboard//FR';

        foreach ($project->tasks as $task) {
            if (!$task->due_date) continue;

            $start = Carbon::parse($task->due_date)->startOfDay()->format('Ymd\THis\Z');
            $end = Carbon::parse($task->due_date)->addHour()->format('Ymd\THis\Z');

            $uid = uniqid() . '@kanboard';

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . $uid;
            $lines[] = 'SUMMARY:' . $this->escapeText($task->title);
            $lines[] = 'DESCRIPTION:' . $this->escapeText($task->description ?? '');
            $lines[] = 'DTSTART:' . $start;
            $lines[] = 'DTEND:' . $end;
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        $ics = implode("\r\n", $lines);

        return response($ics, 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'inline; filename="project-' . $project->id . '.ics"',
        ]);
    }

    protected function escapeText(string $text): string
    {
        return str_replace(
            ['\\', ';', ',', "\n", "\r"],
            ['\\\\', '\;', '\,', '\n', ''],
            $text
        );
    }

}
