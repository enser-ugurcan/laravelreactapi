<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'selling_price',
        'original_price',
        'qty',
        'image',
        'featured',
        'popular',
        'status',
    ];

    protected $with = ['category'];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','id');
    }
    public static function productCount($cat_id){
        $cat_id = Product::where(['category_id'=>$cat_id,'status'=>1])->count();
        return $catCount();
    }
    public function comment()
    {
        return $this->hasMany(Comment::class, 'product_id','id');
    }

    public function ProductDescriptions()
    {
        return $this->hasMany(Produc_description::class, 'product_id', 'id')->with('language');
    }
}
