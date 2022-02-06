<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'sender_id',
        'receiver_id',
        'sent_amount',
        'received_amount',
        'sent_currency',
        'received_currency',
        'converted_from',
        'convertion_response',
        'convertion_rate',
        'stauts'
    ];
}
