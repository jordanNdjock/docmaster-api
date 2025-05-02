<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TypeDocument extends Model
{
    use HasFactory;
    public $table      = 'types_documents';
    public $incrementing = false;
    protected $keyType    = 'string';
    protected $fillable   = ['id', 'titre', 'libelle', 'frais','recompense', 'validite','date_expiration', 'supprime'];

    protected $hidden = [
        'supprime'
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

     public function scopeActive($query)
     {
         return $query->where('supprime', false);
     }
 
     public function scopeInactive($query)
     {
         return $query->where('supprime', true);
     }
}
