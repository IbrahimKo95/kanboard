@extends('layouts.app')

@section('content')
    @php
        $view = 'projects.view.kanban';
        $sessionKey = 'project_' . $project->id . '_view';
        
        if (session()->has($sessionKey)) {
            switch (session($sessionKey)) {
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

    <!-- Barre de navigation des vues -->
    <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $project->name }}</h1>
                <p class="text-gray-600 mt-1">{{ $project->description ?? 'Aucune description' }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500 font-medium">Vue actuelle :</span>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <a href="{{ route('projects.kanban', $project) }}" 
                       class="flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ (session($sessionKey) ?? 'kanban') === 'kanban' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Kanban
                    </a>
                    <a href="{{ route('tasks.list', $project) }}" 
                       class="flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ (session($sessionKey) ?? 'kanban') === 'list' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Liste
                    </a>
                    <a href="{{ route('projects.calendar', $project) }}" 
                       class="flex items-center px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 {{ (session($sessionKey) ?? 'kanban') === 'calendar' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M4 11h16M4 19h16M4 15h16"></path>
                        </svg>
                        Calendrier
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="flex space-x-6 text-sm">
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="font-medium">{{ $project->tasks->count() }}</span>
                <span class="ml-1">tâches</span>
            </div>
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="font-medium">{{ $project->users->count() }}</span>
                <span class="ml-1">membres</span>
            </div>
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                <span class="font-medium">{{ $project->columns->count() }}</span>
                <span class="ml-1">colonnes</span>
            </div>
        </div>
    </div>

    {{-- Suppression du formulaire d'invitation ici, il est maintenant dans le modal global du layout --}}

    @include($view)
@endsection
