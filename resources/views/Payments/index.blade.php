@extends('base')

@section('title', 'Gestion des paiements')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des paiements</h1>
        <a href="{{ route('admin.home') }}" 
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

    <!-- MODAL OVERLAY -->
<div id="formModal" 
class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">

<!-- MODAL CONTAINER -->
<div class="bg-white rounded-lg shadow-lg w-full max-w-3xl">
   <div class="p-6">
       <h2 class="text-xl font-semibold text-gray-800 mb-4" id="formTitle">
           Ajouter un nouveau paiement
       </h2>

       <form id="paymentForm" method="POST" 
             action="{{ route('admin.payments.store_pmnt') }}" 
             class="grid grid-cols-1 md:grid-cols-2 gap-4">
           @csrf
           <input type="hidden" id="editId" name="editId" value="">
           <input type="hidden" name="idKw" value="">

           <div class="bg-blue-50 p-3 rounded-md col-span-2">
               <p class="text-sm text-blue-700">
                   L'ID KirooWorld sera généré automatiquement lors de l'enregistrement.
               </p>
           </div>

           <!-- Name with Search -->
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Nom & Prénom</label>
               <input type="text" name="user_name" id="user_name" class="w-full px-3 py-2 border rounded-md" required
                      placeholder="Commencez à taper pour rechercher..." autocomplete="off">
           </div>

           <!-- Email -->
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
               <input type="email" name="email" id="user_email" class="w-full px-3 py-2 border rounded-md" required readonly>
           </div>
           
           <!-- Hidden select for user search -->
           <select id="user_search" class="hidden"></select>

           <!-- Pack -->
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

           <!-- Montant -->
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Montant ($)</label>
               <input type="number" step="0.01" name="amount" class="w-full px-3 py-2 border rounded-md" required>
           </div>

           <!-- Paiement -->
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

           <!-- Statut -->
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
               <select name="status" class="w-full px-3 py-2 border rounded-md" required>
                   <option value="">Sélectionner un statut</option>
                   <option value="Initialisé">Initialisé</option>
                   <option value="Validé">Validé</option>
                   <option value="Échoué">Échoué</option>
               </select>
           </div>

           <!-- Code Facture -->
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Code Facture</label>
               <input type="text" name="invoice_code" class="w-full px-3 py-2 border rounded-md" required>
           </div>

           <!-- Date -->
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
               <input type="date" name="date" class="w-full px-3 py-2 border rounded-md" required>
           </div>

           <!-- Heure -->
           <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Heure</label>
               <input type="time" name="time" class="w-full px-3 py-2 border rounded-md" required>
           </div>

           <!-- Boutons -->
           <div class="flex items-end space-x-2 col-span-2">
               <button type="submit" 
                       class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md flex-1">
                   <span id="submitText">Ajouter le paiement</span>
               </button>
               <button type="button" id="cancelEdit" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md">
                   Annuler
               </button>
           </div>
       </form>
   </div>
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
    document.addEventListener("DOMContentLoaded", () => {
        // Initialize variables
        let userSearchTimeout;
        const userSearchInput = $('#user_name');
        const userEmailInput = $('#user_email');
        const userSearchSelect = $('#user_search');
        
        // Initialize hidden select2 for user search
        userSearchSelect.select2({
            ajax: {
                url: '{{ route("admin.payments.search-users") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 2,
            placeholder: 'Rechercher un utilisateur...',
            width: '100%',
            dropdownParent: $('#formModal')
        });

        // When a user is selected, update the name and email fields
        userSearchSelect.on('select2:select', function (e) {
            const data = e.params.data;
            const name = data.text.split(' (')[0];
            userSearchInput.val(name);
            userEmailInput.val(data.email);
        });

        // Handle search when typing in the name field
        userSearchInput.on('input', function() {
            clearTimeout(userSearchTimeout);
            
            const searchTerm = $(this).val().trim();
            if (searchTerm.length < 2) {
                userSearchSelect.empty().trigger('change');
                return;
            }
            
            userSearchTimeout = setTimeout(() => {
                // Trigger the search
                userSearchSelect.select2('open');
                
                // Manually trigger the search
                const searchUrl = userSearchSelect.data('select2').options.options.ajax.url;
                $.getJSON(searchUrl, { q: searchTerm })
                    .done(function(data) {
                        const results = data.map(item => ({
                            id: item.id,
                            text: item.text,
                            email: item.email
                        }));
                        
                        // Update the select2 with new results
                        userSearchSelect.empty().select2({
                            data: results,
                            dropdownParent: $('#formModal')
                        });
                        
                        // If there's only one result, select it automatically
                        if (results.length === 1) {
                            userSearchSelect.val(results[0].id).trigger('change');
                            userSearchSelect.trigger('select2:select');
                        }
                    });
            }, 500);
        });

        // Clear email when name is cleared
        userSearchInput.on('change', function() {
            if (!$(this).val()) {
                userEmailInput.val('');
            }
        });

        const form = document.getElementById("paymentForm");
        const modal = document.getElementById("formModal");
        const tableBody = document.getElementById("tableBody");
        const cancelBtn = document.getElementById("cancelEdit");
    
        // ouverture/fermeture modal
        document.getElementById("toggleForm").addEventListener("click", () => {
            modal.classList.remove("hidden");
        });
        cancelBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
        });
        modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.classList.add("hidden");
        });
    
        // soumission AJAX
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
    
            const formData = new FormData(form);
    
            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest", // important pour que $request->ajax() soit true
                        "X-CSRF-TOKEN": form.querySelector("input[name=_token]").value,
                    },
                    body: formData
                });
    
                const result = await response.json();
    
                if (result.success) {
                    const payment = result.data;
    
                    // insérer la nouvelle ligne dans le tableau
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td class="px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></td>
                        <td class="px-4 py-3 text-sm text-gray-700">${payment.idKw}</td>
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
                    `;
    
                    // ajouter en haut du tableau
                    tableBody.prepend(row);
    
                    // reset + fermer la modal
                    form.reset();
                    modal.classList.add("hidden");
                } else {
                    alert(result.message || "Erreur inconnue lors de l'ajout du paiement");
                }
            } catch (err) {
                console.error(err);
                alert("Erreur AJAX lors de l'ajout du paiement");
            }
        });
    });
    </script>
    
@endsection
 