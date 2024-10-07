<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuid, HasFactory;
    protected $fillable = [
        'user_id',
        'discount',
        'total',
        'coupon_id',
        'note'
    ];
}
