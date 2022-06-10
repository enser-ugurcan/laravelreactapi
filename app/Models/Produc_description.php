<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produc_description extends Model
{
    use HasFactory;
    protected $table = 'produc_description';
    protected $fillable = [
        'description',
        'parent_id',
        'language_id',
        'meta_title',
    ];
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
