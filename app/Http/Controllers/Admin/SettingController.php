<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(){
        $globalQuota = Setting::get('global_quota', 10485760); // 10 MB por defecto
        $banned = Setting::get('banned_extensions', 'exe,bat,js,php,sh');
        return view('admin.settings.index', compact('globalQuota', 'banned'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'global_quota' => 'required|numeric|min:1024', // mínimo 1KB
            'banned_extensions' => 'required|string',
        ]);

        Setting::updateOrCreate(
            ['key' => 'global_quota'],
            ['value' => $request->global_quota]
        );

        Setting::updateOrCreate(
            ['key' => 'banned_extensions'],
            ['value' => strtolower(trim($request->banned_extensions))]
        );

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
