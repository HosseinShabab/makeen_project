<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable= [
        'loan_number',
        'price',
        'user_description',
        'type',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 0377e06132f02ae7049521598bafce9007e12da5
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }
<<<<<<< HEAD
=======
>>>>>>> c7ebf90e39f431d887b652d84481dccf9972c5e7
=======
>>>>>>> 0377e06132f02ae7049521598bafce9007e12da5
}
