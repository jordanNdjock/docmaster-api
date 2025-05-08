<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Abonnement extends Model
{
    use HasFactory;
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
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

    public function scopeActive($query)
    {
        return $query->where('supprime', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('supprime', true);
    }
}
