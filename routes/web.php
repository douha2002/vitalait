<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipementImportController;
use App\Imports\EquipementsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AssignmentController;
use App\Models\Assignment;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Artisan;
use Phpml\Classification\KNearestNeighbors;
use Illuminate\Http\Request;
use App\Mail\LowStockAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Equipement;
use App\Http\Controllers\ContratsController;
use App\Models\Contrat;
use App\Models\Employee;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FournisseurController;




Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');


Route::get('/home', [HomeController::class, 'index'])->name('home');

// Settings route for the admin
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::put('/settings/update/{user}', [UserController::class, 'update'])->name('settings.update');
    Route::delete('/settings/delete/{user}', [UserController::class, 'delete'])->name('settings.delete');
    Route::put('/settings/approve/{id}', [UserController::class, 'approve'])->name('settings.approve');
    Route::put('/settings/reject/{id}', [UserController::class, 'reject'])->name('settings.reject');
    Route::put('/settings/account', [UserController::class, 'updateAccount'])->name('settings.updateAccount');

});


Route::middleware(['auth'])->group(function () {
    Route::get('/equipments/search', [EquipmentController::class, 'search'])->name('equipments.search');
    Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');
    Route::post('/equipments/store', [EquipmentController::class, 'store'])->name('equipments.store');
    Route::post('/equipments/import', [EquipmentController::class, 'import'])->name('equipments.import'); 
    Route::put('/equipments/{numero_de_serie}', [EquipmentController::class, 'update'])->name('equipments.update');
    Route::delete('/equipments/delete/{numero_de_serie}', [EquipmentController::class, 'destroy'])->name('equipments.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/assignments/search', [AssignmentController::class, 'search'])->name('assignments.search');
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments/store', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::delete('/assignments/{id}/soft-delete', [AssignmentController::class, 'softDelete'])->name('assignments.softDelete');
    Route::patch('/assignments/{id}/restore', [AssignmentController::class, 'restore'])->name('assignments.restore');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('/maintenances/search', [MaintenanceController::class, 'search'])->name('maintenances.search');
    Route::get('/maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
    Route::get('/maintenances/create', [MaintenanceController::class, 'create'])->name('maintenances.create');
    Route::post('/maintenances', [MaintenanceController::class, 'store'])->name('maintenances.store');
    Route::get('/maintenances/{id}/edit', [MaintenanceController::class, 'edit'])->name('maintenances.edit');
    Route::put('/maintenances/{id}', [MaintenanceController::class, 'update'])->name('maintenances.update');

});
Route::middleware(['auth'])->group(function () {
    Route::get('/stock/search', [StockController::class, 'search'])->name('stock.search');
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/available', [StockController::class, 'getAvailableEquipments'])->name('stock.available');
    Route::post('/stock/add', [StockController::class, 'addToStock'])->name('stock.add');
    Route::post('/stock/update-seuil/{sous_categorie}', [StockController::class, 'updateSeuil'])->name('stock.updateSeuil');
    Route::post('/stock/add', [StockController::class, 'addToStock'])->name('stock.add');


});
Route::middleware(['auth'])->group(function () {
    Route::get('/employes/search', [EmployeController::class, 'search'])->name('employes.search');
    Route::get('/employes', [EmployeController::class, 'index'])->name('employes.index');
    Route::post('/employes/import', [EmployeController::class, 'import'])->name('employes.import');
    Route::get('/employes/create', [EmployeController::class, 'create'])->name('employes.create');
    Route::post('/employes/store', [EmployeController::class, 'store'])->name('employes.store');
    Route::get('/employes/{matricule}/edit', [EmployeController::class, 'edit'])->name('employes.edit');
    Route::put('/employes/{matricule}', [EmployeController::class, 'update'])->name('employes.update');
    Route::delete('/employes/{id}', [EmployeController::class, 'destroy'])->name('employes.destroy');
    Route::get('/employes/data', [EmployeController::class, 'getData'])->name('employes.data');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/fournisseurs/search', [FournisseurController::class, 'search'])->name('fournisseurs.search');
        Route::get('/fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');
        Route::post('/fournisseurs/import', [FournisseurController::class, 'import'])->name('fournisseurs.import');
        Route::get('/fournisseurs/create', [FournisseurController::class, 'create'])->name('fournisseurs.create');
        Route::post('/fournisseurs/store', [FournisseurController::class, 'store'])->name('fournisseurs.store');
        Route::get('/fournisseurs/{id}/edit', [FournisseurController::class, 'edit'])->name('fournisseurs.edit');
        Route::put('/fournisseurs/{id}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
        Route::delete('/fournisseurs/{id}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
        
        });

        Route::middleware(['auth'])->group(function () {
            Route::get('/contrats/search', [ContratsController::class, 'search'])->name('contrats.search');
            Route::get('/contrats', [ContratsController::class, 'index'])->name('contrats.index');
            Route::post('/contrats/import', [ContratsController::class, 'import'])->name('contrats.import');
            Route::get('/contrats/create', [ContratsController::class, 'create'])->name('contrats.create');
            Route::post('/contrats/store', [ContratsController::class, 'store'])->name('contrats.store');
            Route::get('/contrats/{id}/edit', [ContratsController::class, 'edit'])->name('contrats.edit');
            Route::put('/contrats/{id}', [ContratsController::class, 'update'])->name('contrats.update');
            Route::delete('/contrats/{id}', [ContratsController::class, 'destroy'])->name('contrats.destroy');
            
            });

Route::get('/api/equipment-count', function () {
    return response()->json([
        'count' => Equipement::count()
    ]);
});

Route::get('/api/assignment-count', function () {
    $count = DB::table('equipements')
        ->where('statut', '=', 'Affecté')
        ->count();

    return response()->json(['count' => $count]);
});

Route::get('/api/maintenance-count', function () {
    $count = Equipement::whereRaw("LOWER(TRIM(statut)) LIKE ?", ['%en panne%'])->count();

    return response()->json(['count' => $count]);
});

Route::get('/api/stock-count', function () {
    return response()->json([
        'count' => Equipement::where('statut', 'En stock')->count()
    ]);
});
Route::get('/api/equipement-by-sous-categorie', function () {
    $data = \App\Models\Equipement::select('sous_categorie', \DB::raw('count(*) as total'))
        ->groupBy('sous_categorie')
        ->get();

    return response()->json($data);
});





