@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Détails de l'utilisateur</h1>
            <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Retour à la liste
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Informations personnelles</h2>
                    <div class="mt-2 space-y-2">
                        <p><span class="font-medium">Nom complet:</span> {{ $user->name }} {{ $user->surname }}</p>
                        <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
                        <p><span class="font-medium">Téléphone:</span> {{ $user->phone ?? 'Non renseigné' }}</p>
                        <p><span class="font-medium">Genre:</span> {{ $user->gender == 'male' ? 'Homme' : 'Femme' }}</p>
                        <p><span class="font-medium">Date de naissance:</span> {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'Non renseignée' }}</p>
                        <p>
                            <span class="font-medium">Statut:</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->status == 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </p>
                        <p><span class="font-medium">Date d'inscription:</span> {{ $user->registration_timestamp ? $user->registration_timestamp->format('d/m/Y H:i') : 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Groupes</h2>
                @if($user->groups && $user->groups->count() > 0)
                    <div class="space-y-2">
                        @foreach($user->groups as $group)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="font-medium">{{ $group->name }}</p>
                                <p class="text-sm text-gray-600">{{ $group->description }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucun groupe attribué</p>
                @endif
            </div>
        </div>

        @if($user->payments && $user->payments->count() > 0)
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Historique des paiements</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border-b border-gray-200 text-left">Date</th>
                            <th class="py-2 px-4 border-b border-gray-200 text-left">Montant</th>
                            <th class="py-2 px-4 border-b border-gray-200 text-left">Méthode</th>
                            <th class="py-2 px-4 border-b border-gray-200 text-left">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b border-gray-200">{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ number_format($payment->amount, 2, ',', ' ') }} €</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $payment->payment_method }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <span class="px-2 py-1 text-xs rounded-full {{ $payment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $payment->status == 'completed' ? 'Complété' : 'En attente' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
