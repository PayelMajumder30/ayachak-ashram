<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $table = "products";
    protected $fillable = ['category_id', 'title','product_code', 'description', 'is_featured','status'];

    public static function generateProductCode()
    {
        $latest = self::latest('id')->first();

        $nextId = $latest ? $latest->id + 1 : 1;
        return 'PRD-' . str_pad($nextId, 6, '0', STR_PAD_LEFT); // e.g., PRD-000123
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function relatedProducts()
    {
        return $this->hasMany(RelatedProduct::class);
    }
}
