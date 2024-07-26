<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Installment extends Model implements HasMedia
{
    use HasFactory , Notifiable, InteractsWithMedia;

    protected $fillable = [
        'count',
        'price',
        'due_date',
        'status',
        'admin_accept',
        'admin_description',
        'paid_price',
        'user_description',

    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

}
