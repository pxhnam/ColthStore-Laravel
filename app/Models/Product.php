<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUuid;
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'desc',
        'state'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
