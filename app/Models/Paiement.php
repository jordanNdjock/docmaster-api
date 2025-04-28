<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paiement extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','transaction_id','docmaster_id','etat','supprime'];

    protected static function booted()
    {
        static::creating(fn($m)=> $m->id = (string) Str::uuid());
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function docmaster()
    {
        return $this->belongsTo(Docmaster::class);
    }
}
