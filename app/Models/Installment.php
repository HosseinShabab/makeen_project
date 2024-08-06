<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Installment extends Model
{
    use HasFactory , Notifiable;

    protected $fillable = [
        'count',
        'type',
        'price',
        'due_date',
        'status',
        'admin_accept',
        'admin_description',
        'user_id',
        'loan_id',
        'payment_id',

    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function factors(): BelongsToMany
    {
        return $this->belongsToMany(Factor::class);
    }

}
