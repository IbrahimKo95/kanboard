@extends('layouts.app')

@section('content')
    @php
        session_start();
        $view = 'projects.view.kanban';

        if (isset($_SESSION[$project->id]['view'])) {
            switch ($_SESSION[$project->id]['view']) {
                case 'list':
                    $view = 'projects.view.list';
                    break;
                case 'kanban':
                    $view = 'projects.view.kanban';
                    break;
                case 'calendar':
                    $view = 'projects.view.calendar';
                    break;
            }
        }
    @endphp

    @include($view)
@endsection
