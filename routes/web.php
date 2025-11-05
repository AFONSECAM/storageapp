<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// --- AUTENTICACIÓN ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- RUTAS PROTEGIDAS ---
Route::middleware(['auth'])->group(function () {

    // Panel de usuario (dashboard)
    Route::get('/dashboard', [FileController::class, 'index'])->name('dashboard');

    // Subida y eliminación de archivos (AJAX / JS)
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::get('/config', [FileController::class, 'getConfig'])->name('files.config');

    // --- PANEL DE ADMINISTRACIÓN ---
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard de admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Gestión de usuarios (solo index para mostrar la vista)
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');

        // Gestión de grupos (solo index para mostrar la vista)
        Route::get('groups', [GroupController::class, 'index'])->name('groups.index');

        // Configuración global
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
