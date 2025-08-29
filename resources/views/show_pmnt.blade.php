@extends('base')

@section('title', 'Gestion des paiements')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des paiements</h1>
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            Retour à l'accueil
        </a>
    </div>

    <!-- BOUTON NOUVEAU -->
    <div class="mb-6">
        <button id="toggleForm" 
                class="action-button bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md shadow-md">
            <i class="fas fa-plus"></i> Nouveau Paiement
        </button>
    </div>

    <!-- FORMULAIRE D’AJOUT -->
    <div id="formContainer" class="form-container bg-white rounded-lg shadow-sm border mb-8 hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4" id="formTitle">
                Ajouter un nouveau paiement
            </h2>
            <form id="paymentForm" method="POST" action="{{ route('admin.dashboard.store_pmnt') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <input type="hidden" id="editId" name="editId" value="">

                <div class="bg-blue-50 p-3 rounded-md">
                    <p class="text-sm text-blue-700">L'ID KirooWorld sera généré automatiquement lors de l'enregistrement.</p>
                    <input type="hidden" name="idKw" value="">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom & Prénom</label>
                    <input type="text" name="user_name" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pack</label>
                    <select name="pack" class="w-full px-3 py-2 border rounded-md" required>
                        <option value="">Sélectionner un pack</option>
                        <option value="Dieu du Mboa">Dieu du Mboa</option>
                        <option value="Pack Premium">Pack Premium</option>
                        <option value="Pack Starter">Pack Starter</option>
                        <option value="Pack Business">Pack Business</option>
                        <option value="Pack Basic">Pack Basic</option>
                        <option value="Pack VIP">Pack VIP</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant ($)</label>
                    <input type="number" step="0.01" name="amount" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Système de paiement</label>
                    <select name="payment_system" class="w-full px-3 py-2 border rounded-md" required>
                        <option value="">Sélectionner un mode</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Orange Money">Orange Money</option>
                        <option value="MTN Money">MTN Money</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Express Union">Express Union</option>
                        <option value="Virement Bancaire">Virement Bancaire</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border rounded-md" required>
                        <option value="">Sélectionner un statut</option>
                        <option value="Initialisé">Initialisé</option>
                        <option value="Validé">Validé</option>
                        <option value="Échoué">Échoué</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code Facture</label>
                    <input type="text" name="invoice_code" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Heure</label>
                    <input type="time" name="time" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div class="flex items-end space-x-2 col-span-2">
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md flex-1">
                        <span id="submitText">Ajouter le paiement</span>
                    </button>
                    <button type="button" id="cancelEdit" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md hidden">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLEAU DES PAIEMENTS -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-yellow-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nom & Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Pack payé</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Montant reçu</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Système paiement</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Code Facture</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date paiement</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200" id="tableBody">
                @foreach ($payments as $payment)
                    <tr>
                        <td class="px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $payment->idKw }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="font-medium">{{ $payment->user_name }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $payment->pack }}</td>
                        <td class="px-4 py-3 text-sm">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $payment->payment_system }}</td>
                        <td class="px-4 py-3 text-sm">{{ $payment->status }}</td>
                        <td class="px-4 py-3 text-sm">{{ $payment->invoice_code }}</td>
                        <td class="px-4 py-3 text-sm">{{ $payment->date }} {{ $payment->time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- JS: toggle & ajout dynamique -->
<script>
document.getElementById("toggleForm").addEventListener("click", () => {
    document.getElementById("formContainer").classList.toggle("hidden");
});

document.getElementById("paymentForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const submitButton = this.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submitText');
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
            // Add the new payment to the table
            const payment = data.data;
            const row = `
                <tr>
                    <td class="px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></td>
                    <td class="px-4 py-3 text-sm">${payment.idKw}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium">${payment.user_name}</div>
                        <div class="text-xs text-gray-500">${payment.email}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">${payment.pack}</td>
                    <td class="px-4 py-3 text-sm">$${parseFloat(payment.amount).toFixed(2)}</td>
                    <td class="px-4 py-3 text-sm">${payment.payment_system}</td>
                    <td class="px-4 py-3 text-sm">${payment.status}</td>
                    <td class="px-4 py-3 text-sm">${payment.invoice_code}</td>
                    <td class="px-4 py-3 text-sm">${payment.date} ${payment.time}</td>
                </tr>`;
            
            document.getElementById("tableBody").insertAdjacentHTML("afterbegin", row);
            this.reset();
            document.getElementById("formContainer").classList.add("hidden");
            
            // Show success message
            alert('Paiement ajouté avec succès!');
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Une erreur est survenue lors de l\'ajout du paiement');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitText.textContent = originalText;
    });
});
</script>
@endsection
