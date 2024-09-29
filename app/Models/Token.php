<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory, HasUuid;
    protected $fillable = [
        'code',
        'timeout',
        'type',
        'user_id'
    ];
}
