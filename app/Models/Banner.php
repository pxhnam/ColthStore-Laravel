<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory, HasUuid;
    protected $fillable = [
        'title',
        'sub',
        'path',
        'state'
    ];
}
