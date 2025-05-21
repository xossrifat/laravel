<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $spinsLeft = 10; // আপনি চাইলে এখানে ডাটাবেস চেক করে হিসেব করতে পারেন

        return view('dashboard', compact('user', 'spinsLeft'));
    }
}
