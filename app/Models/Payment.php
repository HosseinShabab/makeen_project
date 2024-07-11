<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable= [
        'type',
        'membership_price',
        'installment_number',
        'transfer_price',
        'payment_date',
        'description',
        'admins_card',

    ];
}