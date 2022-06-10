<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = '_product_images';
    protected $fillable = [
    'product_id',
    'image',
 
    ];
}
