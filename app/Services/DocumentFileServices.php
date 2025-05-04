<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentFileServices
{
    public function storeFile(UploadedFile $file, string $folder = 'documents'): string
    {       
            $fileName = time().'_'.$file->getClientOriginalName();
            return $file->storeAs($folder, $fileName, 'public');
    }

    public function deleteFile(string $filePath) : bool
    {
        if(Storage::disk('public')->exists($filePath)){
            return Storage::disk('public')->delete($filePath);
        }
        return false;
    }

    public function updateFile(UploadedFile $file, string $folder, string $id): string
    {
        $document = Document::findOrFail($id);
        $existingPath = $document->fichier_url;
        if($existingPath && Storage::disk('public')->exists($existingPath)){
            Storage::disk('public')->delete($existingPath);
        }
        return $this->storeFile($file, $folder);
    }
}