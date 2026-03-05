<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'harga',
        'stock',
        'deskripsi'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}