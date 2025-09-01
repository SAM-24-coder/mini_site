@extends('base')

@section('title','Liste des Groupes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">Liste des Groupes d'utilisateur Kiroo</h1>
    <p class="text-gray-600 mb-4">Dans cette section, vous pouvez gérer les groupes, en créer de nouveaux, supprimer et voir les détails.</p>

    <!-- Actions -->
    <div class="mb-6">
        <button id="toggleForm" 
                class="action-button bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md shadow-md">
            <i class="fas fa-plus"></i> Nouveau Groupe
        </button>
    </div>
    <!--- formulaire d'ajout--->
    <div id="formContainer" class="form-container bg-white rounded-lg shadow-sm border mb-8 hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4" id="formTitle">
                Ajouter un nouveau groupe
            </h2>
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
                       <form method="POST" action="{{ route('admin.groups.destroy',$group) }}">
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

@section('scripts')
<script>
    // Toggle form visibility
    document.getElementById("toggleForm").addEventListener("click", () => {
        document.getElementById("formContainer").classList.toggle("hidden");
    });
    
    // Handle form submission
    document.querySelector("form").addEventListener("submit", function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        const submitText = submitButton.querySelector('span');
        const originalText = submitText.textContent;
        
        // Show loading state
        submitButton.disabled = true;
        submitText.textContent = 'Traitement...';
        
        let formData = new FormData(this);
        
        fetch(this.action, {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Add the new group to the table
                const group = data.data;
                const row = `
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: #${group.color}"></span>
                                <span>${group.name}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">#${group.tag}</td>
                        <td class="px-4 py-3">${group.description || '-'}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-500">${new Date(group.created_at).toLocaleDateString()}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-500">${group.creator?.name || 'Inconnu'}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-500">${group.updater?.name || 'Inconnu'}</span>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="/admin/groups/${group.id}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Supprimer</button>
                            </form>
                        </td>
                    </tr>`;
                
                document.querySelector("table tbody").insertAdjacentHTML("afterbegin", row);
                this.reset();
                document.getElementById("formContainer").classList.add("hidden");
                
                // Show success message
                alert('Groupe ajouté avec succès!');
            } else {
                throw new Error(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Une erreur est survenue lors de la création du groupe');
        })
        .finally(() => {
            // Reset button state
            if (submitButton && submitText) {
                submitButton.disabled = false;
                submitText.textContent = originalText;
            }
        });
    });
</script>
@endsection
