<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'password',
        'phone_number'  ,
        'emergency_number',
        'home_number',
        'national_code',
        'card_number',
        'sheba_number',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => "hashed",
        "address"=> "object",
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function Loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }


    public function messages(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);

    }

<<<<<<< HEAD
    public function factors(): HasMany
    {
        return $this->hasMany(Factor::class);
    }

=======
    public function Setting(): HasOne
    {
        return $this->hasOne(Setting::class);
    }
>>>>>>> 806ae2a576b5872e83623eb0445a8ca432488c1f
}
