<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipementImportController;
use App\Imports\EquipementsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AssignmentController;
use App\Models\Assignment;


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


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
    Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');
    Route::post('/equipments/store', [EquipmentController::class, 'store'])->name('equipments.store');
    Route::post('/equipments/import', [EquipmentController::class, 'import'])->name('equipments.import'); 
    Route::put('/equipments/{numero_de_serie}', [EquipmentController::class, 'update'])->name('equipments.update');
    Route::delete('/equipments/{numero_de_serie}', [EquipmentController::class, 'destroy'])->name('equipments.destroy');
    Route::get('/search', [SearchController::class, 'search'])->name('search');

});
Route::middleware(['auth'])->group(function () {
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments/store', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('/equipments/{numero_de_serie}/history', [AssignmentController::class, 'showHistory'])->name('assignments.history');
});




