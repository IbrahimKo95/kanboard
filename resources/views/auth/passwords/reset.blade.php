<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 w-full max-w-md mt-12">
        <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center">Nouveau mot de passe</h1>
        @if($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="block mb-1 font-medium text-gray-700">Adresse email</label>
                <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="password" class="block mb-1 font-medium text-gray-700">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="password_confirmation" class="block mb-1 font-medium text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition">Réinitialiser le mot de passe</button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline text-sm">Retour à la connexion</a>
        </div>
    </div>
</div> 