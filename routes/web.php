<?php
require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// Note: Illuminate\Support\Facades\Input est deprecated depuis Laravel 5.2
use App\Models\Userdb;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Version corrigée avec la syntaxe appropriée
/*Route::prefix('admin')->name('admin.')->controller(UserController::class)->group(function() {
    Route::get('/home', 'index')->name('home');
    Route::get('/users', 'show')->name('show');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/create_pmnt', 'create_pmnt')->name('create_pmnt');
    Route::post('/store_pmnt', 'store_pmnt')->name('store_pmnt');
    Route::get('/show_pmnt', 'show_pmnt')->name('show_pmnt');
    Route::get('/show_group', 'show_group')->name('show_group');
    Route::get('/create_group', 'create_group')->name('create_group');
    Route::post('/store_group', 'store_group')->name('store_group');
    Route::delete('/destroy_group/{group}', 'destroy_group')->name('destroy_group');
});*/



Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
    
    Route::get('/', [AdminController::class, 'index'])->name('home');
    // === GESTION DES UTILISATEURS ===
    Route::prefix('users')->name('users.')->controller(UserController::class)->group(function() {
        Route::get('/', 'index')->name('index');           // admin.users.index - Liste des utilisateurs
        Route::get('/create', 'create')->name('create');   // admin.users.create_user - Formulaire création
        Route::post('/', 'store')->name('store');          // admin.users.store - Enregistrer utilisateur
        Route::get('/{id}', 'show')->name('show');       // admin.users.show - Voir un utilisateur
    });
    
    // === GESTION DES PAIEMENTS ===
    Route::prefix('payments')->name('payments.')->controller(PaymentController::class)->group(function() {
        Route::get('/', 'index')->name('index');           // admin.payments.index - Liste des paiements
        Route::get('/search-users', 'searchUsers')->name('search-users'); // admin.payments.search-users - Recherche d'utilisateurs
        Route::get('/create', 'create_pmnt')->name('create_pmnt');   // admin.payments.create_pmnt - Créer paiement
        Route::post('/', 'store_pmnt')->name('store_pmnt');          // admin.payments.store - Enregistrer paiement
        //Route::get('/{id}', 'show')->name('show');    // admin.payments.show - Voir paiement
    });
    
    // === GESTION DES GROUPES ===
    Route::prefix('groups')->name('groups.')->controller(GroupController::class)->group(function() {
        Route::get('/', 'index')->name('index');           // admin.groups.index - Liste des groupes
        Route::get('/create', 'create_group')->name('create');   // admin.groups.create - Créer groupe
        Route::post('/', 'store_group')->name('store_group');          // admin.groups.store - Enregistrer groupe
        Route::get('/{id}', 'show')->name('show');      // admin.groups.show - Voir groupe
        Route::delete('/{id}', 'destroy')->name('destroy');      // admin.groups.destroy - Supprimer groupe
    });
    
});


/*
AVANT:                           APRÈS:
admin.home                  →    admin.dashboard
admin.show                  →    admin.users.index
admin.create                →    admin.users.create
admin.store                 →    admin.users.store
admin.create_pmnt          →    admin.payments.create
admin.store_pmnt           →    admin.payments.store
admin.show_pmnt            →    admin.payments.index
admin.show_group           →    admin.groups.index
admin.create_group         →    admin.groups.create
admin.store_group          →    admin.groups.store
admin.destroy_group        →    admin.groups.destroy
*/

