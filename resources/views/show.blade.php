@extends('base')

@section('title','Liste des utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des utilisateurs</h1>
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            Retour à l'accueil
        </a>
    </div>

    <!-- Layout principal en deux colonnes -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- COLONNE DE GAUCHE : Filtres et tableau (3/4 de la largeur) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- FILTRES ET RECHERCHE -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-4"> Rechercher et filtrer</h2>
                
                <form method="GET" action="{{ route('admin.dashboard.show') }}" class="space-y-4">
                    <!-- Ligne des champs de filtre -->
                    <div class="flex flex-wrap gap-4">
                        <!-- Recherche par nom -->
                        <div class="flex-1 min-w-64">
                            <label for="search_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Rechercher par nom/prénom
                            </label>
                            <input type="text" 
                                name="search_name" 
                                id="search_name" 
                                value="{{ request('search_name') }}" 
                                placeholder="Saisir le nom ou prénom..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Filtrage par statut -->
                        <div class="flex-1 min-w-48">
                            <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">
                                Filtrer par statut
                            </label>
                            <select name="status_filter" id="status_filter" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status_filter') === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="blocked" {{ request('status_filter') === 'blocked' ? 'selected' : '' }}>Bloqué</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Ligne des boutons -->
                    <div class="flex gap-2 pt-2">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition duration-200 font-medium">
                            Rechercher
                        </button>
                        <a href="{{ route('admin.dashboard.show') }}" 
                        class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md transition duration-200 font-medium">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <!-- INFORMATIONS SUR LES RÉSULTATS -->
            <div class="p-3 bg-blue-50 border-l-4 border-blue-400 text-sm text-gray-700 rounded">
                <div class="flex flex-wrap gap-4 items-center">
                    <span class="font-semibold">Résultats : {{ $users->total() }} utilisateur(s)</span>
                    
                    @if(request('search_name'))
                        <span class="bg-yellow-100 px-2 py-1 rounded text-yellow-800">
                            Recherche : "{{ request('search_name') }}"
                        </span>
                    @endif
                    
                    @if(request('status_filter'))
                        <span class="bg-blue-100 px-2 py-1 rounded text-blue-800">
                            Statut : {{ request('status_filter') === 'active' ? 'Actif' : 'Bloqué' }}
                        </span>
                    @endif
                    
                    <!--@if(request('sort_name'))
                        <span class="bg-green-100 px-2 py-1 rounded text-green-800">
                            Tri : {{ request('sort_name') === 'asc' ? 'A → Z' : 'Z → A' }}
                        </span>
                    @endif-->
                </div>
            </div>

            <!-- TABLEAU DES UTILISATEURS -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Pagination en haut -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    {{ $users->links() }}
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>Nom & Prénom</span>
                                        @if(request('sort_name') === 'asc')
                                            <span class="text-blue-500 font-bold">↑</span>
                                        @elseif(request('sort_name') === 'desc')
                                            <span class="text-blue-500 font-bold">↓</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Téléphone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Inscrit le
                                </th>
                                <!--<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>-->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <!-- Checkbox -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <input type="checkbox" 
                                               name="users[]" 
                                               value="{{ $user->id }}" 
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    
                                    <!-- Nom & Prénom -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }} {{ $user->surname }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ID: {{ $user->id }}
                                        </div>
                                    </td>
                                    
                                    <!-- Email -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </td>
                                    
                                    <!-- Téléphone -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $user->phone ?? 'N/A' }}
                                        </div>
                                    </td>
                                    
                                    <!-- Statut -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->status)
                                            @if($user->status === 'active')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ✓ Actif
                                                </span>
                                            @elseif($user->status === 'blocked')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    ✗ Bloqué
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $user->status }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Non défini
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Date d'inscription -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($user->registration_timestamp)
                                            <div class="text-sm text-gray-900">
                                                {{ $user->registration_timestamp->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $user->registration_timestamp->format('H:i:s') }}
                                            </div>
                                        @elseif($user->created_at)
                                            <div class="text-sm text-gray-900">
                                                {{ $user->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $user->created_at->format('H:i:s') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Actions -->
                                   <!-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 transition duration-150">
                                                 Voir
                                            </a>
                                            <a href="#" class="text-yellow-600 hover:text-yellow-900 transition duration-150">
                                                 Modifier
                                            </a>
                                            @if($user->status === 'active')
                                                <a href="#" class="text-red-600 hover:text-red-900 transition duration-150">
                                                     Bloquer
                                                </a>
                                            @else
                                                <a href="#" class="text-green-600 hover:text-green-900 transition duration-150">
                                                     Activer
                                                </a>
                                            @endif
                                        </div>
                                    </td>--->
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            @if(request('search_name') || request('status_filter'))
                                                <div class="text-lg mb-2">🔍 Aucun résultat</div>
                                                <p>Aucun utilisateur ne correspond à vos critères de recherche.</p>
                                                <a href="{{ route('admin.dashboard.show') }}" 
                                                   class="mt-3 inline-block text-blue-600 hover:text-blue-800 underline">
                                                    Afficher tous les utilisateurs
                                                </a>
                                            @else
                                                <div class="text-lg mb-2"> Aucun utilisateur</div>
                                                <p>Aucun utilisateur n'a été trouvé dans la base de données.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination en bas -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        <!-- COLONNE DE DROITE : Statistiques (1/4 de la largeur) -->
        <div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
            Statistiques système
        </h2>

        <!-- Tableau des statistiques -->
        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            %
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Total -->
                    <tr class="bg-blue-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            Total
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($stats['total']) }}
                        </td>
                        
                    </tr>
                    
                    <!-- Actifs -->
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            Actifs
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($stats['active']['count']) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-medium">
                            {{ $stats['active']['percentage'] }}%
                        </td>
                    </tr>
                    
                    <!-- Bloqués -->
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            inactif 
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($stats['inactif']['count']) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-medium">
                            {{ $stats['inactif']['percentage'] }}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Barres de progression -->
        <!-- <div class="space-y-4 mb-6">
            <div>
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span>Actifs</span>
                    <span>{{ $stats['active']['percentage'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['active']['percentage'] }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span>Bloqués</span>
                    <span>{{ $stats['inactif']['percentage'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['inactif']['percentage'] }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span>Inactifs</span>
                    <span>{{ $stats['inactif']['percentage'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gray-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['inactif']['percentage'] }}%"></div>
                </div>
            </div>
        </div> -->
    </div>
</div>
    </div>
</div>
@endsection