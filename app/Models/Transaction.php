<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id','user_id','transactionable_id','transactionable_type','statut','montant','identifiant'
    ];

    protected static function booted()
    {
        static::creating(fn($m)=> $m->id = (string) Str::uuid());
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUsers($query, $userId){
        return $query->where('user_id', $userId);
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
