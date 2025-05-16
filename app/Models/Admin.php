<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory, HasApiTokens;
    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = ['id', 'nom_utilisateur', 'email', 'mdp', 'tel', 'supprime'];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    protected $hidden = [
        'mdp',
        'supprime'
    ];
    public function casts(){
        return [
            'mdp' => 'hashed',
        ];
    }
}
