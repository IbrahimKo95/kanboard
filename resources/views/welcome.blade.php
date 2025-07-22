<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 w-full max-w-md mt-12 text-center">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition">Se déconnecter</button>
        </form>
    </div>
</div>

@vite(['resources/css/app.css', 'resources/js/app.js'])
