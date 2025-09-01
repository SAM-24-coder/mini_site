<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\View\View;
use App\Models\User;


class PaymentController extends Controller
{
    //
   
    public function index(): View
    {
        $payments = Payment::latest()->paginate(10);
        return view('Payments.index', compact('payments'));
    }   
    
    /**
     * Génère un ID KirooWorld unique
     * Format: KW-YYYYMMDD-XXXXX (où X sont des chiffres aléatoires)
     */
    private function generateKirooWorldId()
    {
        $prefix = 'KW-' . date('Ymd') . '-';
        $maxTries = 100;
        
        for ($i = 0; $i < $maxTries; $i++) {
            $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $idKw = $prefix . $random;
            
            if (!Payment::where('idKw', $idKw)->exists()) {
                return $idKw;
            }
        }
        
        throw new \RuntimeException('Impossible de générer un ID KirooWorld unique après plusieurs tentatives');
    }

    public function store_pmnt(Request $request)
    {
        $validated = $request->validate([
            'user_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255|exists:users,email',
            'pack'           => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'payment_system' => 'required|string|max:255',
            'status'         => 'required|string|max:255',
            'invoice_code'   => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (Payment::where('invoice_code', $value)->exists()) {
                        $fail('Ce code facture existe déjà.');
                    }
                },
            ],
            'date'           => 'required|date',
            'time'           => 'required',
        ]);
        
        // Find the user by email to get their ID
        $user = User::where('email', $validated['email'])->first();
        
        // Generate a unique KirooWorld ID
        $validated['idKw'] = $this->generateKirooWorldId();
        
        // Add user_id to the validated data
        if ($user) {
            $validated['user_id'] = $user->id;
        }
        
        try {
            // Create the payment
            $payment = Payment::create($validated);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement ajouté avec succès',
                    'data' => $payment
                ]);
            }

            return redirect()->route('admin.payments.index')->with('success', 'Paiement ajouté avec succès.');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout du paiement: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Erreur lors de l\'ajout du paiement: ' . $e->getMessage());
        }
    }

}
