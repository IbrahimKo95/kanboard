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
        <span class="text-xl font-bold text-blue-600">📈 Kanboard</span>
        <div class="text-sm text-gray-500">Projet : <span class="font-medium text-gray-700">{{ $project->name }}</span></div>
    </div>
    <div class="flex items-center gap-4">
        <a class="border border-gray-500 text-gray-500 px-4 py-2 rounded hover:bg-gray-600 hover:text-white transition duration-200 flex items-center">
            Inviter des utilisateurs
        </a>
        <a data-modal-target="modalCreateColumn" data-modal-toggle="modalCreateColumn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200 flex items-center">
            Nouvelle colonne
            <svg class="inline-block w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </a>
        <input type="text" placeholder="Rechercher..." class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs text-white font-bold {{ \Illuminate\Support\Facades\Auth::user()->avatar()['color'] }}" alt="Avatar utilisateur">
            {{ \Illuminate\Support\Facades\Auth::user()->avatar()['initials'] }}
        </div>
    </div>
</nav>

<div class="flex h-[calc(100vh-5rem)]">
    <aside class="w-64 bg-white shadow-lg h-[calc(100vh-5rem)] fixed top-20 left-0 z-20 p-6 border-r border-gray-200">
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
    <main class="ml-64 w-full overflow-y-auto p-6 mt-20">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>


</html>
