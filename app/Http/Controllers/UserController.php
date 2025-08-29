<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use App\Models\Payment;
use App\Models\Group;

class UserController extends Controller
{
    // Page d'accueil du dashboard
    public function index(): View
    {
        return view('index');
    }
    
    // Afficher la liste des utilisateurs
    public function show(Request $request): View
{
    $query = User::query();
    
    // Recherche par nom / prénom / email
    if ($request->filled('search_name')) {
        $searchTerm = trim($request->get('search_name'));
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('surname', 'LIKE', "%{$searchTerm}%")
              ->orWhere('email', 'LIKE', "%{$searchTerm}%");
        });
    }
    
    // Filtrage par statut
    if ($request->filled('status_filter')) {
        $statusFilter = $request->get('status_filter');
        if (in_array($statusFilter, ['active', 'inactive'])) {
            $query->where('status', $statusFilter);
        }
    }
    
    // Tri par nom ou par défaut
    if ($request->filled('sort_name') && in_array($request->get('sort_name'), ['asc', 'desc'])) {
        $query->orderBy('name', $request->get('sort_name'));
    } else {
        $query->orderBy('id', 'desc');
    }
    
    // Pagination
    $users = $query->paginate(10)->appends($request->query());
    
    // Stats
    $totalUsers    = User::count();
    $activeUsers   = User::where('status', 'active')->count();
    $inactiveUsers = User::where('status', 'inactive')->count();

    $stats = [
        'total'    => $totalUsers,
        'active'   => [
            'count'      => $activeUsers,
            'percentage' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0
        ],
        'inactive' => [
            'count'      => $inactiveUsers,
            'percentage' => $totalUsers > 0 ? round(($inactiveUsers / $totalUsers) * 100, 1) : 0
        ],
    ];
    
    return view('show', compact('users', 'stats'));
}

    
    // Afficher le formulaire de création
    public function create(): View
    {
        return view('create');
    }
    
    // Enregistrer un nouvel utilisateur
    public function create_user(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'surname'               => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'nullable|string|max:20',
            'gender'                => 'required|in:male,female',
            'date_of_birth'         => 'nullable|date',
            'status'                => 'required|in:active,inactif',
            'password'              => 'required|min:8',
            'password_confirmation' => 'required|min:8',
        ]);
    
        // Validation manuelle des mots de passe
        if ($request->input('password') !== $request->input('password_confirmation')) {
            return back()->withErrors(['password' => 'Les mots de passe ne correspondent pas.'])->withInput();
        }
    
        $validated = $request->only([
            'name', 'surname', 'email', 'phone', 
            'gender', 'date_of_birth', 'status', 'password'
        ]);
    
        $validated['password'] = bcrypt($validated['password']);
        $validated['registration_timestamp'] = now();
    
        User::create($validated);
        
        return redirect()->route('admin.dashboard.show')->with('success', 'Utilisateur créé avec succès');
    }

    public function create_pmnt(): View
    {
        return view('create_pmnt');
    }

    public function show_pmnt(): View
    {
        $payments = Payment::latest()->paginate(10);
        return view('show_pmnt', compact('payments'));
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
            'email'          => 'required|email|max:255',
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
        
        $validated['idKw'] = $this->generateKirooWorldId();

        try {
            $payment = Payment::create($validated);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement ajouté avec succès',
                    'data' => $payment
                ]);
            }

            return redirect()->route('admin.dashboard.show_pmnt')->with('success', 'Paiement ajouté avec succès.');
            
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

    public function show_group(): View
    {
        $groups = Group::latest()->paginate(10);
        return view('show_group', compact('groups'));
    }
    
    public function create_group(): View
    {
        return view('create_group');
    }
    
    public function store_group(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'tag' => 'required|string|max:255|unique:groups,tag',
        'description' => 'nullable|string',
    ]);

    $validated['created_by'] = auth()->id();

    Group::create($validated);

    return redirect()->route('admin.dashboard.show_group')->with('success', 'Groupe créé avec succès');
}
    public function destroy(Group $group)
    {
        $group->delete();
        return back()->with('success', 'Groupe supprimé');
    }
}
