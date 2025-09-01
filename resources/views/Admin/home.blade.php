@extends('base')

@section('title','Accueil de mon site')

@section('content')
<div class="max-w-4xl mx-auto text-center">
    <h1 class="text-4xl font-bold mb-8 text-gray-800">Bienvenue sur la page d'accueil</h1>    
    
    <div class="space-y-4">
        <a href="{{ route('admin.users.create') }}" 
           class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
            Créer un utilisateur
        </a>
        
        <a href="{{ route('admin.users.index') }}" 
           class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ml-4">
            Voir la liste des utilisateurs
        </a>

        <a href="{{ route('admin.payments.index') }}" 
           class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ml-4">
            Voir la liste des paiements
        </a>
        <a href="{{ route('admin.groups.index') }}" 
           class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ml-4">
            Voir la liste des groupes
        </a>
       <!-- <a href="{{ route('admin.groups.create') }}" 
           class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ml-4">
            Créer un groupe
        </a> -->
    </div>
</div>
@endsection