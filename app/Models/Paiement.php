<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paiement extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','transaction_id','etat','supprime'];

    protected static function booted()
    {
        static::creating(fn($m)=> $m->id = (string) Str::uuid());
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function getUserAttribute()
    {
        return $this->transaction->user;
    }
}
