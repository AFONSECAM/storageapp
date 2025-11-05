<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Setting;
use Illuminate\Http\Request;
use ZipArchive;

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

    public function store(Request $request){
        $request->validate(['file' => 'required|file']);
        $user = auth()->user();
        $file = $request->file('file');
        $size = $file->getSize();
        $ext = strtolower($file->getClientOriginalExtension());

        $globalQuota = (int) Setting::get('global_quota', 10485760);
        $banned = array_map('trim', explode(',', Setting::get('banned_extensions','exe,bat,js,php,sh')));

        $quota = $user->quota_limit ?? ($user->group->quota_limit ?? $globalQuota);
        $used = (int) $user->files()->sum('size');

        if (in_array($ext, $banned)) {
            return response()->json(['error' => "Tipo de archivo .{$ext} no permitido"], 422);
        }


        if (($used + $size) > $quota) {
            return response()->json(['error' => "Cuota excedida ({$quota} bytes)"], 422);
        }

        if ($ext === 'zip') {
            $zip = new ZipArchive;
            if ($zip->open($file->getRealPath()) === TRUE) {
                for ($i=0; $i < $zip->numFiles; $i++) {
                    $inner = $zip->getNameIndex($i);
                    $innerExt = strtolower(pathinfo($inner, PATHINFO_EXTENSION));
                    if (in_array($innerExt, $banned)) {
                        $zip->close();
                        return response()->json(['error' => "Archivo '{$inner}' dentro del .zip no permitido"], 422);
                    }
                }
                $zip->close();
            }
        }

        // Guardar
        $path = $file->store("uploads/{$user->id}", 'public');
        $record = File::create([
            'user_id' => $user->id,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $size
        ]);

        return response()->json(['message' => 'Archivo subido correctamente']);
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        $file->delete();
        return back();
    }
}
