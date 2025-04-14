<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrat;

class ContratsController extends Controller
{
    public function dashboard()
    {
        $totalContrats = Contrat::count();
        $expiredContrats = Contrat::where('date_fin', '<', now())->count();
        $activeContrats = $totalContrats - $expiredContrats;

        return view('home', compact('totalContrats', 'expiredContrats', 'activeContrats'));
    }
}
