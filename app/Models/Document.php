<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    public $incrementing = false;
    protected $keyType  = 'string';
    protected $fillable = [
        'id', 'nature_id', 'user_id',
        'contenu', 'trouve', 'sauvegarde',
        'signale', 'supprime'
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
}
