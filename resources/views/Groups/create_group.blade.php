@extends('base')

@section('title', 'Créer un groupe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">Créer un nouveau groupe</h1>
    
    <form method="POST" action="{{ route('admin.groups.store_group') }}" class="space-y-4">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nom du groupe</label>
            <input type="text" name="name" id="name" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <div>
            <label for="tag" class="block text-sm font-medium text-gray-700">Tag (sans le #)</label>
            <input type="text" name="tag" id="tag" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>
        
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.groups.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Annuler
            </a>
            <button type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Créer le groupe
            </button>
        </div>
    </form>
</div>
@endsection