<?php

namespace App\Services;

use App\Models\Setting;
use ZipArchive;

class FileValidationService
{
    public function validateFile($file, $user): ?string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $size = $file->getSize();
        
        // Validar extensión
        if ($error = $this->validateExtension($ext)) {
            return $error;
        }
        
        // Validar cuota
        if ($error = $this->validateQuota($size, $user)) {
            return $error;
        }
        
        // Validar ZIP
        if ($error = $this->validateZip($file, $ext)) {
            return $error;
        }
        
        return null;
    }
    
    private function validateExtension(string $ext): ?string
    {
        $banned = $this->getBannedExtensions();
        
        if (in_array($ext, $banned)) {
            return "Tipo de archivo .{$ext} no permitido";
        }
        
        return null;
    }
    
    private function validateQuota(int $size, $user): ?string
    {
        $globalQuota = (int) Setting::get('global_quota', 10485760);
        $quota = $user->quota_limit !== null ? $user->quota_limit : ($user->group->quota_limit ?? $globalQuota);
        $used = (int) $user->files()->sum('size');
        
        if (($used + $size) > $quota) {
            return "Cuota excedida ({$quota} bytes)";
        }
        
        return null;
    }
    
    private function validateZip($file, string $ext): ?string
    {
        if ($ext !== 'zip') {
            return null;
        }
        
        $zip = new ZipArchive;
        if ($zip->open($file->getRealPath()) !== TRUE) {
            return null;
        }
        
        $banned = $this->getBannedExtensions();
        
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $inner = $zip->getNameIndex($i);
            $innerExt = strtolower(pathinfo($inner, PATHINFO_EXTENSION));
            
            if (in_array($innerExt, $banned)) {
                $zip->close();
                return "Archivo '{$inner}' dentro del .zip no permitido";
            }
        }
        
        $zip->close();
        return null;
    }
    
    private function getBannedExtensions(): array
    {
        return array_map('trim', explode(',', Setting::get('banned_extensions', 'exe,bat,js,php,sh')));
    }
}