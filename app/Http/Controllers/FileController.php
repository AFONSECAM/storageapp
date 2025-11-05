<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Setting;
use App\Services\FileValidationService;
use App\Services\FileStorageService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(){
        $user = auth()->user();
        $files = $user->files()->orderBy('created_at', 'desc')->get();
        $used = $user->files()->sum('size');
        $globalQuota = Setting::get('global_quota', 10485760);
        $quota = $user->quota_limit ?? ($user->group->quota_limit ?? $globalQuota);
        return view('user.dashboard', compact('files', 'used', 'quota'));        
    }

    public function store(Request $request, FileValidationService $validator, FileStorageService $storage)
    {
        $request->validate(['file' => 'required|file']);
        
        $user = auth()->user();
        $file = $request->file('file');
        
        // Validar archivo
        if ($error = $validator->validateFile($file, $user)) {
            return response()->json(['error' => $error], 422);
        }
        
        // Almacenar archivo
        $storage->store($file, $user);
        
        return response()->json(['message' => 'Archivo subido correctamente']);
    }

    public function destroy(File $file, FileStorageService $storage)
    {
        $user = auth()->user();
        
        // Verificar permisos
        if ($user->id !== $file->user_id && $user->role !== 'admin') {
            abort(403, 'No tienes permisos para eliminar este archivo');
        }
        
        // Eliminar archivo
        $storage->delete($file);
        
        return response()->json(['message' => 'Archivo eliminado correctamente']);
    }

    public function getConfig()
    {
        $banned = Setting::get('banned_extensions', 'exe,bat,js,php,sh');
        return response()->json([
            'banned_extensions' => array_map('trim', explode(',', $banned))
        ]);
    }
}
