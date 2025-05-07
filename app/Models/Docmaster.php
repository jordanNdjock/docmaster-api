<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Docmaster extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id','doc_chercheur_id','doc_trouveur_id','document_id',
        'nombre_notif','credit','debit','confirm','code_confirm','supprime'
    ];

    protected static function booted()
    {
        static::creating(function($m){
            $m->id = (string) Str::uuid();
            $m->code_confirm = self::generateCodeConfirm();
        });
    }

    public function chercheur()
    {
        return $this->belongsTo(User::class, 'doc_chercheur_id');
    }
    public function trouveur()
    {
        return $this->belongsTo(User::class, 'doc_trouveur_id');
    }
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function scopeActive($query){
        return $query->where('supprime', false);
    }

    public function scopeArchived($query){
        return $query->where('supprime', true);
    }

    public static function generateCodeConfirm(int $length = 5): string
    {
        do {
            $code = Str::random($length);
        } while (self::where('code_confirm', $code)->exists());

        return $code;
    }
}

