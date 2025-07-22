<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Kanboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900 h-screen overflow-hidden">

<nav class="fixed top-0 left-0 right-0 z-10 bg-white shadow-sm px-6 py-3 flex justify-between items-center border-b border-gray-200 h-20">
    <div class="flex items-center gap-4">
        <!-- Hamburger menu for mobile -->
        <button id="sidebarToggle" class="block md:hidden mr-2 text-gray-700 focus:outline-none" aria-label="Ouvrir le menu">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <a href="/" class="text-xl font-bold text-blue-600 hover:text-blue-800 transition flex items-center">
            📈 Kanboard
        </a>
        <div class="text-sm text-gray-500 hidden sm:block">Projet : <span class="font-medium text-gray-700">{{ $project->name ?? '—' }}</span></div>
    </div>
    <div class="flex items-center gap-4">
        @if(isset($project))
        <a data-modal-target="modalInviteUser" data-modal-toggle="modalInviteUser" class="border border-gray-500 text-gray-500 px-4 py-2 rounded hover:bg-gray-600 hover:text-white transition duration-200 flex items-center hidden sm:flex cursor-pointer">
            Inviter des utilisateurs
        </a>
        <a data-modal-target="modalCreateColumn" data-modal-toggle="modalCreateColumn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200 flex items-center hidden sm:flex">
            Nouvelle colonne
            <svg class="inline-block w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </a>
        @endif
        <input type="text" placeholder="Rechercher..." class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-blue-500 focus:border-blue-500 hidden sm:block">
        @auth
        <div class="relative flex items-center gap-2">
            <!-- Onglet notifications -->
            <div class="relative group">
                <button id="notifBtn" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-blue-100 focus:outline-none relative" aria-label="Notifications">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @php
                        $notifCount = \App\Models\Invitation::where('receiver_id', \Illuminate\Support\Facades\Auth::id())->where('status', 0)->count();
                    @endphp
                    @if($notifCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5">{{ $notifCount }}</span>
                    @endif
                </button>
                <div class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded shadow-lg py-2 z-50 hidden group-hover:block group-focus-within:block max-h-96 overflow-y-auto">
                    <div class="px-4 py-2 font-semibold text-gray-700 border-b">Invitations</div>
                    @php
                        $invitations = \App\Models\Invitation::with(['project', 'sender'])->where('receiver_id', \Illuminate\Support\Facades\Auth::id())->where('status', 0)->get();
                    @endphp
                    @forelse($invitations as $inv)
                        <div class="px-4 py-3 border-b flex flex-col gap-1">
                            <div class="text-sm text-gray-800">Projet : <span class="font-semibold">{{ $inv->project->name ?? 'Projet supprimé' }}</span></div>
                            <div class="text-xs text-gray-500">Invité par : {{ $inv->sender->fullName() ?? 'Utilisateur supprimé' }}</div>
                            <div class="flex gap-2 mt-2">
                                <form method="POST" action="{{ route('invitations.accept', $inv->id) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">Accepter</button>
                                </form>
                                <form method="POST" action="{{ route('invitations.refuse', $inv->id) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-gray-300 text-gray-700 rounded text-xs hover:bg-gray-400">Refuser</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-6 text-center text-gray-400">Aucune invitation reçue.</div>
                    @endforelse
                </div>
            </div>
            <!-- Bulle utilisateur -->
            <div class="relative group">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs text-white font-bold {{ \Illuminate\Support\Facades\Auth::user()->avatar()['color'] }} cursor-pointer" alt="Avatar utilisateur">
                    {{ \Illuminate\Support\Facades\Auth::user()->avatar()['initials'] }}
                </div>
                <div class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-lg py-2 z-50 hidden group-hover:block group-focus-within:block">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mon profil</a>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Se déconnecter</button>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </div>
</nav>

<!-- Sidebar responsive : hidden on mobile, overlay on mobile when open -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>
@auth
<aside id="sidebar" class="w-64 bg-white shadow-lg h-[calc(100vh-5rem)] fixed top-20 left-0 z-40 p-6 border-r border-gray-200 hidden md:block transition-transform duration-200">
        <nav class="flex flex-col gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Projets</h2>
                <ul class="space-y-2">
                    @foreach (\Illuminate\Support\Facades\Auth::user()->projects as $proj)
                        <li>
                            <a href="{{route('projects.show', [$proj])}}" class="inline-flex items-center gap-2 text-blue-600 bg-blue-50 border border-blue-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2 3h10v9H3V7z" />
                                </svg>
                                {{ $proj->name }}
                            </a>
                        </li>
                    @endforeach
            </div>
            <hr class="border-gray-200 my-4">
            <a href="#" class="inline-flex items-center gap-2 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2 3h10v9H3V7z" />
                </svg>
                Equipe
            </a>
            <a href="#" class="inline-flex items-center gap-2 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2 3h10v9H3V7z" />
                </svg>
                Paramètres
            </a>
        </nav>
    </aside>
@endauth

<div class="flex h-[calc(100vh-5rem)]">
    <main class="w-full overflow-y-auto p-2 sm:p-6 mt-20 transition-all duration-200 md:ml-64">
        @yield('content')
    </main>
</div>

<script>
// Sidebar responsive JS
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const sidebarToggle = document.getElementById('sidebarToggle');
if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.remove('hidden');
        sidebarOverlay.classList.remove('hidden');
    });
}
if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.add('hidden');
        sidebarOverlay.classList.add('hidden');
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

<!-- Modal de création de colonne -->
<div id="modalCreateColumn" tabindex="-1" class="hidden fixed inset-0 bg-black/30 z-50 flex justify-center items-center">
    <div class="relative w-full max-w-lg bg-white rounded-lg shadow p-6">
        <button data-modal-hide="modalCreateColumn" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900">
            ✕
        </button>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Créer une nouvelle colonne</h3>
        <form method="POST" action="{{ route('columns.store', ['project' => $project ?? 1]) }}">
            @csrf
            <div class="mb-4">
                <label for="column_name" class="block mb-1 font-medium text-gray-900">Nom de la colonne</label>
                <input type="text" name="name" id="column_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label for="column_color" class="block mb-1 font-medium text-gray-900">Couleur</label>
                <input type="color" name="color" id="column_color" class="w-full border rounded px-3 py-2" value="#3B82F6">
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="finished_column" class="mr-2">
                    <span class="text-sm text-gray-700">Colonne de fin (tâches terminées)</span>
                </label>
            </div>

            <div class="text-right">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Créer</button>
            </div>
        </form>
    </div>
</div>

@if(isset($project))
<!-- Modal d'invitation utilisateur -->
<div id="modalInviteUser" tabindex="-1" class="hidden fixed inset-0 bg-black/30 z-50 flex justify-center items-center">
    <div class="relative w-full max-w-lg bg-white rounded-lg shadow p-6">
        <button data-modal-hide="modalInviteUser" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900">✕</button>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Inviter un utilisateur</h3>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-2 border border-green-200 text-center">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->has('email'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-2 border border-red-200 text-center">
                {{ $errors->first('email') }}
            </div>
        @endif
        <form method="POST" action="{{ route('projects.invite', $project) }}" class="flex gap-2">
            @csrf
            <input type="email" name="email" placeholder="Email de l'utilisateur" required class="flex-1 border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Inviter</button>
        </form>
    </div>
</div>
@endif

</body>


</html>
