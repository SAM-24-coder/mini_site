<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    // Page d'accueil du dashboard
    public function index(): View
    {
        return view('Admin.home');
    }
}
