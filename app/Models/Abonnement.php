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
        'montant',
        'supprime'
    ];

    protected $hidden = [
        'supprime'
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function abonnementsUsers()
    {
        return $this->hasMany(AbonnementUser::class);
    }    

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function scopeActive($query)
    {
        return $query->where('supprime', true);
    }

    public function scopeArchived($query)
    {
        return $query->where('supprime', false);
    }
}
