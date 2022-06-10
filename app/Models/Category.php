<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'language_id',
        'meta_title',
        'slug',
        'name',
        'description',
        'status,'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function CategoryDescriptions()
    {
        return $this->hasMany(CategoryDescription::class, 'category_id', 'id')->with('language');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->with('CategoryDescriptions')->with('categories');
    }
}
