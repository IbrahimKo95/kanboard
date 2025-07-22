<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 w-full max-w-md mt-12">
        <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center">Inscription</h1>
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <div>
                <label for="firstname" class="block mb-1 font-medium text-gray-700">Prénom</label>
                <input type="text" name="firstname" id="firstname" placeholder="Prénom" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="lastname" class="block mb-1 font-medium text-gray-700">Nom</label>
                <input type="text" name="lastname" id="lastname" placeholder="Nom" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="email" class="block mb-1 font-medium text-gray-700">Adresse email</label>
                <input type="email" name="email" id="email" placeholder="Adresse email" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="password" class="block mb-1 font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="password_confirmation" class="block mb-1 font-medium text-gray-700">Confirmation</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmation" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition">S'inscrire</button>
        </form>
    </div>
</div>

@vite(['resources/css/app.css', 'resources/js/app.js'])
