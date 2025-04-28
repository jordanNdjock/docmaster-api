<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Nature extends Model
{
    public $incrementing = false;
    protected $keyType    = 'string';
    protected $fillable   = ['id', 'libelle', 'supprime'];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }
    
    public function champs()
    {
        return $this->hasMany(Champ::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function parametres()
    {
        return $this->hasOne(Parametre::class);
    }
}
