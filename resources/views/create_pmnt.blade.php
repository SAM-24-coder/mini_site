@extends('base')

@section('title','Ajouter un paiement')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Ajouter un paiement</h1>
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Retour à l'accueil
        </a>
    </div>
    
    <form method="POST" action="{{ route('admin.dashboard.store_pmnt') }}" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                <input type="text" name="name" id="name" required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                <input type="text" name="surname" id="surname" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                <input type="text" name="phone" id="phone" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="male">Active</option>
                    <option value="female">Supprime</option>
                </select>
            </div>
            
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                <input type="date" name="date_of_birth" id="date_of_birth" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            
        </div>
        
        <div class="mt-6">
            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                Créer l'utilisateur
            </button>
        </div>
    </form>
</div>
@endsection