<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'setting';
    protected $fillable = [
        'title',
        'keyword',
        'description',
        'company',
        'address',
        'phone',
        'fax',
        'email',
        'smtpserver',
        'smtpemail',
        'smtppassword',
        'smtpport',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'aboutus',
        'references'
    ];

}
