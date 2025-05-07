<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AbonnementUser extends Model
{

    protected $fillable = [
        'abonnement_id',
        'user_id',
        'date_debut',
        'date_expiration',
        'supprime'
    ];

    protected $hidden = [
        'supprime'
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
