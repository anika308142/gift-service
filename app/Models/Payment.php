<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'id',
        'user_id',
        'transaction_id',
        'amount',
        'status',
        'payment_details',

    ];

    protected $casts = [
        'payment_details' => 'array'
    ];

}
