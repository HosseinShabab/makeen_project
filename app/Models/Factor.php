<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Factor extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'name',
        'installment_price',
        'discription',
        'paid_price',
        'accept_status',
        'user_id',
        'factor'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function installments(): BelongsToMany
    {
        return $this->belongsToMany(Installment::class);
    }
}
