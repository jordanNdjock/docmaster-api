<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id','user_id','type_trans','statut','reference','identifiant','supprime'
    ];

    protected static function booted()
    {
        static::creating(fn($m)=> $m->id = (string) Str::uuid());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function paiements()
    {
        return $this->hasOne(Paiement::class);
    }
}
