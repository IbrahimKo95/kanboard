@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-semibold">Nom du projet</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block font-semibold">Description</label>
            <textarea name="description" id="description"
                class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror"
                rows="4">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
    </form>

</div>
@endsection