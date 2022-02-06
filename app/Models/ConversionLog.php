<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'sent_currency',
        'received_currency',
        'converted_from',
        'convertion_response',
        'convertion_rate',
    ];
}
