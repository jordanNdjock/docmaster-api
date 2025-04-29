<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Type_Document extends Model
{
    public $incrementing = false;
    protected $keyType    = 'string';
    protected $fillable   = ['id', 'titre', 'frais', 'libelle','recompense', 'validite','date_expiration', 'supprime'];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
