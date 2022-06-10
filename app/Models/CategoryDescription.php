<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDescription extends Model
{
    use HasFactory;
    protected $table = 'category_descriptions';
    protected $fillable = [
    'title',
    'description',
    'language_id',
    'category_id',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
