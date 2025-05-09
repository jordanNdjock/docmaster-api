<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentFileServices
{

    private string $DOCUMENT_FOLDER = 'documents/documents'.auth()->user()->nom_utilisateur;

    public function storeFile(UploadedFile $file): string
    {       
            $fileName = time().'_'.$file->getClientOriginalName();
            return $file->storeAs($this->DOCUMENT_FOLDER, $fileName, 'public');
    }

    public function deleteFile(string $id) : bool
    {
        $document = Document::findOrFail($id);
        $filePath = $document->fichier_url;
        if(Storage::disk('public')->exists($filePath)){
            return Storage::disk('public')->delete($filePath);
        }
        return false;
    }

    public function updateFile(UploadedFile $file, string $id): string
    {
        $document = Document::findOrFail($id);
        $existingPath = $document->fichier_url;
        if($existingPath && Storage::disk('public')->exists($existingPath)){
            Storage::disk('public')->delete($existingPath);
        }
        return $this->storeFile($file);
    }
}