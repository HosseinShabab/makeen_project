<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Message extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia,HasRoles;
    protected $fillable = [
        "description",
        "status",
        "user_id",
        "title",
        "priority",
        "message",
        "ticket_id",

    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

}
