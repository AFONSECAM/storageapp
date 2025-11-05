<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se definen las rutas API para peticiones AJAX (fetch / Axios)
| desde el frontend. Requieren autenticación mediante sanctum o sesión.
|
*/

// 🔐 Middleware de autenticación con sesión o token (usa sanctum si lo activas)
Route::middleware('auth')->group(function () {

    // --- 📂 API de archivos del usuario ---
    Route::get('/files', [FileController::class, 'index']);           // Listar archivos del usuario
    Route::post('/files', [FileController::class, 'store']);          // Subir archivo
    Route::delete('/files/{file}', [FileController::class, 'destroy']); // Eliminar archivo

    // --- 🧑‍💼 API de administración ---
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Usuarios
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::post('/users', [AdminUserController::class, 'store']);
        Route::get('/users/{user}', [AdminUserController::class, 'show']);
        Route::put('/users/{user}', [AdminUserController::class, 'update']);
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);

        // Grupos
        Route::get('/groups', [GroupController::class, 'index']);
        Route::post('/groups', [GroupController::class, 'store']);
        Route::get('/groups/{group}', [GroupController::class, 'show']);
        Route::put('/groups/{group}', [GroupController::class, 'update']);
        Route::delete('/groups/{group}', [GroupController::class, 'destroy']);

        // Configuración global
        Route::get('/settings', [SettingController::class, 'index']);
        Route::post('/settings', [SettingController::class, 'update']);
    });
});
