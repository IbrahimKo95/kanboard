@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 w-full max-w-md mt-12">
        <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center">Mon profil</h1>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 border border-green-200 text-center">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            <div>
                <label for="firstname" class="block mb-1 font-medium text-gray-700">Prénom</label>
                <input type="text" name="firstname" id="firstname" value="{{ old('firstname', $user->firstname) }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="lastname" class="block mb-1 font-medium text-gray-700">Nom</label>
                <input type="text" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold transition">Enregistrer</button>
        </form>
        <div class="mt-4 text-center">
            <a href="/" class="text-blue-600 hover:underline text-sm">Retour à l'accueil</a>
        </div>
    </div>
</div>
@endsection 