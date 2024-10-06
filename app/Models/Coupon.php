<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasUuid, HasFactory;
    protected $fillable = [
        'code',
        'value',
        'type',
        'min',
        'max',
        'limit',
        'desc',
        'start_date',
        'expiry_date'
    ];
}
