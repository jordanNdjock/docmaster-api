<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AbonnementUser extends Model
{

    protected $fillable = [
        'abonnement_id',
        'user_id',
        'actif',
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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class, 'abonnement_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function isActive(): bool
    {
        $active = Carbon::now()->lte(Carbon::parse($this->date_expiration)->endOfDay());

        if ((bool) $this->actif !== $active) {
            $this->actif = $active;
            $this->save();
        }

        return $active;
    }
}
