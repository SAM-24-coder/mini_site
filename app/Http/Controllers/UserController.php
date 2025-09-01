<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use App\Models\Payment;
use App\Models\Group;

class UserController extends Controller
{
    
    
    // Afficher la liste des utilisateurs
    public function index(Request $request): View
    {
        $query = $this->buildSearchQuery($request);
        $query = $this->applyFilters($query, $request);
        
        $users = $query->paginate(10)->appends($request->query());
        $stats = $this->getUserStats();
        
        return view('Users.index', compact('users', 'stats'));
    }
    
    protected function buildSearchQuery(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('search_name')) {
            $searchTerm = trim($request->get('search_name'));
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('surname', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        return $query;
    }
    
    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('status_filter')) {
            $statusFilter = $request->get('status_filter');
            if (in_array($statusFilter, ['active', 'inactive'])) {
                $query->where('status', $statusFilter);
            }
        }
        
        return $query;
    }
    
    protected function getUserStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
    
        return [
            'total' => $totalUsers,
            'active' => [
                'count' => $activeUsers,
                'percentage' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0
            ],
            'inactive' => [
                'count' => $inactiveUsers,
                'percentage' => $totalUsers > 0 ? round(($inactiveUsers / $totalUsers) * 100, 1) : 0
            ],
        ];
    }
    
    // Afficher le formulaire de création
    public function create(): View
    {
        return view('Users.create');
    }
    
    // Enregistrer un nouvel utilisateur
    public function store(Request $request)
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
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::with(['payments', 'groups'])->findOrFail($id);
        return view('Users.show', compact('user'));
    }

   
    
}
