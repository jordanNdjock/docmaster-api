<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Abonnement extends Model
{
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'titre',
        'nombre_docs_par_type',
        'date_debut',
        'date_expiration',
        'montant',
        'nom_organisation'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_expiration' => 'datetime'
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
