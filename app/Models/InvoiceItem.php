<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasUuid, HasFactory;
    protected $fillable = [
        'invoice_id',
        'variant_id',
        'cost',
        'num'
    ];
}
