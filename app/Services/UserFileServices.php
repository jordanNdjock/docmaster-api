<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserFileServices
{

    private string $USER_FOLDER;

    public function __construct()
    {
        $this->USER_FOLDER = 'users/photos/' . auth()->user()->nom_utilisateur;
    }
    public function storeFile(UploadedFile $file): string
    {       
            $fileName = time().'_'.$file->getClientOriginalName();
            return $file->storeAs($this->USER_FOLDER, $fileName, 'public');
    }

    public function deleteFile(string $id) : bool
    {
        $user = User::findOrFail($id);
        $filePath = $user->photo_url;
        if(Storage::disk('public')->exists($filePath)){
            return Storage::disk('public')->delete($filePath);
        }
        return false;
    }

    public function updateFile(UploadedFile $file, string $id): string
    {
        $user = User::findOrFail($id);
        $existingPath = $user->photo_url;
        if($existingPath && Storage::disk('public')->exists($existingPath)){
            Storage::disk('public')->delete($existingPath);
        }
        return $this->storeFile($file);
    }
}