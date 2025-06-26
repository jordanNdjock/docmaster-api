<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Retrait extends Model
{
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'etat',
        'tel',
        'montant',
        'date',
    ];

    protected static function booted()
    {
        static::creating(fn($m)=> $m->id = (string) Str::uuid());
    }

     public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
