<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public static function upload(UploadedFile $file, string $folder, $disk = 'public'): string
    {
        // Obtener el nombre del archivo sin la extensión
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Obtenemos la extensión
        $extension = $file->getClientOriginalExtension();
        
        // Sobreescribimos la variable filename añadiendo la marca de tiempo
        $filename = $filename . '_' . time() . '.' . $extension;
        
        // Lo almacenamos usando el método storeAs
        $file->storeAs($folder, $filename, $disk);
        
        return $filename;
    }

    public static function delete(string $path, $disk = 'public'): bool
    {
        // Nos aseguramos de que esté el archivo
        if (!Storage::disk($disk)->exists($path)) {
            return false;
        }

        return Storage::disk($disk)->delete($path);
    }

    public static function url(string $path, $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }
}

