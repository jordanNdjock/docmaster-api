<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Admin extends Model
{
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = ['id', 'nom_utilisateur', 'email', 'mdp', 'tel', 'supprime'];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }
}
