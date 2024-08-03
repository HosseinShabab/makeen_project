<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'admin_accept',
        'due_date',
        'description',
        'price',
    ];

    public function installments(): BelongsToMany
    {
        return $this->belongsToMany(Installment::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
