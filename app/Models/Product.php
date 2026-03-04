<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
    'nama',
    'harga',
    'stock',
    'deskripsi',
    'foto'
    ];
}
