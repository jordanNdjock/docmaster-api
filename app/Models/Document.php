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
        'fichier_url', 'trouve', 'sauvegarde', 'date_expiration',
        'signale', 'supprime', 'nom_proprietaire'
    ];

    protected $hidden = [
        'supprime',
    ];

    protected $appends = [
        'created_at_human'
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function type_document()
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function docmasters()
    {
        return $this->hasMany(Docmaster::class);
    }

    public function scopeActive($query){
        return $query->where('supprime', false);
    }

    public function scopeArchived($query){
        return $query->where('supprime', true);
    }
    
    public function scopeUsers($query, $userId){
        return $query->where('user_id', $userId);
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
