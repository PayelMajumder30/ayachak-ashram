<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = "products";
    protected $fillable = ['title', 'product_code', 'description', 'is_featured','status'];
}
