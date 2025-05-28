<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AbonnementUser extends Model
{

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'abonnement_id',
        'user_id',
        'actif',
        'date_debut',
        'date_expiration',
        'supprime'
    ];

    protected $appends = [
        'nombre_docs_utilises_par_type'
    ];

    protected $hidden = [
        'supprime'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_expiration' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class, 'abonnement_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function isActive(): bool
    {
        $active = now()->lte($this->date_expiration->endOfDay());;

        if ((bool) $this->actif !== $active) {
            $this->actif = $active;
            $this->saveQuietly();
        }

        return $active;
    }

    public function getNombreDocsUtilisesParTypeAttribute()
    {
        $userId = $this->user_id;

        $rows = Document::query()
            ->select('titre', DB::raw('COUNT(*) AS total'))
            ->where('user_id', $userId)
            ->where('sauvegarde', true)
            ->groupBy('titre')
            ->get();

        return $rows->pluck('total', 'titre')->toArray();
    }
}
