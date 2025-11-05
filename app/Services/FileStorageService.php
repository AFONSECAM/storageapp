<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    public function store($uploadedFile, $user): File
    {
        $path = $uploadedFile->store("uploads/{$user->id}", 'public');
        
        return File::create([
            'user_id' => $user->id,
            'name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'size' => $uploadedFile->getSize()
        ]);
    }
    
    public function delete(File $file): void
    {
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        
        $file->delete();
    }
}