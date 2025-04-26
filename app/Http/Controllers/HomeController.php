<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // === FILTRE sous_categorie ===
        $selectedCategorie = $request->input('categorie');

        $CategoriesList = Equipement::select('categorie')
                                        ->distinct()
                                        ->pluck('categorie');

        $totalCategories = $CategoriesList->count();

        $countForSelected = $selectedCategorie 
            ? Equipement::where('categorie', $selectedCategorie)->count()
            : null;

        // === CONTRATS === (à remplacer par les vraies requêtes)
        $totalContrats = DB::table('contrats')->count();
        $expiredContrats = DB::table('contrats')->whereDate('date_fin', '<', now())->count();
        $activeContrats = DB::table('contrats')->whereDate('date_fin', '>=', now())->count();


        // === TAUX DE ROULEMENT PAR ÉQUIPEMENT ===
        $tauxDeRoulement = DB::table('assignments')
            ->select('equipements.numero_de_serie', DB::raw('COUNT(assignments.id) as total_roulements'))
            ->join('equipements', 'equipements.numero_de_serie', '=', 'assignments.numero_de_serie')
            ->groupBy('equipements.numero_de_serie')
            ->get();

        // === TOP 5 PANNES EN ANNÉE COURANTE ===
        $top5Pannes = DB::table('maintenances')
            ->join('equipements', 'maintenances.numero_de_serie', '=', 'equipements.numero_de_serie')
            ->select('equipements.article', DB::raw('COUNT(*) as total_pannes'))
            ->whereYear('maintenances.created_at', now()->year)
            ->groupBy('equipements.article')
            ->orderByDesc('total_pannes')
            ->limit(5)
            ->get();

        // === RENDER VIEW ===
        return view('home', [
            'totalCategories' => $totalCategories,
            'CategoriesList' => $CategoriesList,
            'selectedCategorie' => $selectedCategorie,
            'countForSelected' => $countForSelected,

            'totalContrats' => $totalContrats,
            'expiredContrats' => $expiredContrats,
            'activeContrats' => $activeContrats,

            'tauxDeRoulement' => $tauxDeRoulement,
            'top5Pannes' => $top5Pannes,
        ]);
    }
}
