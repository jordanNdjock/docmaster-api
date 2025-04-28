<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Champ extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id', 'nature_id', 'libelle', 'number', 'position', 'supprime'
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function nature()
    {
        return $this->belongsTo(Nature::class);
    }
}
