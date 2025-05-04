<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    public $incrementing = false;
    protected $keyType  = 'string';
    protected $fillable = [
        'id', 'type_document_id', 'user_id', 'titre',
        'fichier_url', 'trouve', 'sauvegarde',
        'signale', 'supprime', 'nom_proprietaire'
    ];

    protected $hidden = [
        "supprime",
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function type_document()
    {
        return $this->belongsTo(TypeDocument::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function docmasters()
    {
        return $this->hasOne(Docmaster::class);
    }

    public function scopeActive($query){
        return $query->where('supprime', false);
    }

    public function scopeArchived($query){
        return $query->where('supprime', true);
    }
    
    public function scopeUser($query){
        return $query->where('user_id', auth()->user()->id);
    }
}
