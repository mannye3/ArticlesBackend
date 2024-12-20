<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'source',
        'category',
        'description',
        'content',
        'url',
        'image_url',
        'published_at',
    ];
}