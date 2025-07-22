@extends('layouts.app')

@section('title', 'Utilisateurs du projet')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold">Utilisateurs du projet : {{ $project->name }}</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($project->users as $user)
            @php
                $avatar = $user->avatar();
            @endphp
            <div class="bg-white rounded-2xl shadow p-6 flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center rounded-full text-white font-bold text-lg {{ $avatar['color'] }}">
                    {{ $avatar['initials'] }}
                </div>

                <div class="flex-1">
                    <h2 class="text-lg font-semibold">{{ $user->fullName() }}</h2>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    @if ($user->pivot && isset($user->pivot->role))
                        <p class="text-xs text-gray-500 mt-1 italic">Rôle : {{ $user->pivot->role }}</p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-600">Aucun utilisateur n'est encore associé à ce projet.</p>
        @endforelse
    </div>
@endsection
