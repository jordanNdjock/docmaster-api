<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $incrementing = false;
    protected $keyType  = 'string';
    protected $fillable = [
        'id','prenom','initial_2_prenom','nom_famille',
        'nom_utilisateur','email','mdp','tel', 'solde',
        'date_naissance','infos_paiement','code_invitation','localisation','supprime'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'supprime',
        'email_verified_at',
        'mdp',
        'remember_token',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->id = (string) Str::uuid();
            $user->code_invitation = self::generateInviteCode();
        });
    }

    public function abonnementUtilisateur()
    {
        return $this->hasOne(AbonnementUser::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function docmasters()
    {
        return $this->hasMany(Docmaster::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mdp' => 'hashed',
        ];
    }

    /**
     * Génère un code d’invitation unique de la longueur souhaitée.
     */
    public static function generateInviteCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (self::where('code_invitation', $code)->exists());

        return $code;
    }
}
