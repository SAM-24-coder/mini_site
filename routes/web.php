<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// Note: Illuminate\Support\Facades\Input est deprecated depuis Laravel 5.2
use App\Models\Userdb;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return view('welcome');
});

// Version corrigée avec la syntaxe appropriée
Route::prefix('admin')->name('admin.')->controller(UserController::class)->group(function() {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::get('/dashboard/users', 'show')->name('dashboard.show');
    Route::get('/dashboard/create', 'create')->name('dashboard.create');
    Route::post('/dashboard/store', 'create_user')->name('dashboard.store');
    Route::get('/dashboard/create_pmnt', 'create_pmnt')->name('dashboard.create_pmnt');
    Route::post('/dashboard/store_pmnt', 'store_pmnt')->name('dashboard.store_pmnt');
    Route::get('/dashboard/show_pmnt', 'show_pmnt')->name('dashboard.show_pmnt');

});

