@extends('base')

@section('title','Liste des Groupes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">Liste des Groupes d'utilisateur Kiroo</h1>
    <p class="text-gray-600 mb-4">Dans cette section, vous pouvez gérer les groupes, en créer de nouveaux, supprimer et voir les détails.</p>

    <!-- Actions -->
    <div class="flex gap-2 mb-4">
        <form method="POST" action="{{ route('admin.dashboard.store_group') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Nom du groupe"
                class="border rounded px-3 py-2">
            <input type="text" name="tag" placeholder="#tag"
                class="border rounded px-3 py-2">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Ajouter</button>
        </form>
    </div>

    <!-- Tableau -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Id</th>
                    <th class="px-4 py-3">Nom du groupe (#Tag)</th>
                    <th class="px-4 py-3">Nombre de Users</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Date de création (Par)</th>
                    <th class="px-4 py-3">Dernière update (Par)</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $i => $group)
                <tr>
                    <td class="px-4 py-3">{{ $i+1 }}</td>
                    <td class="px-4 py-3">{{ $group->id }}</td>
                    <td class="px-4 py-3">
                        <div class="text-blue-600 font-semibold">{{ $group->name }}</div>
                        <div class="text-sm text-gray-500">#{{ $group->tag }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $group->users->count() }}</td>
                    <td class="px-4 py-3">{{ $group->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">
                        {{ $group->created_at->format('d/m/Y H:i') }}<br>
                        <span class="text-xs text-gray-500">Par {{ $group->creator->name ?? 'Inconnu' }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ $group->updated_at->format('d/m/Y H:i') }}<br>
                        <span class="text-xs text-gray-500">Par {{ $group->updater->name ?? 'Inconnu' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.dashboard.destroy_group',$group) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
