<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('title', 'description', 'status', 'published_at')]
class Post extends Model
{
    use HasFactory;
    public $timestamps = ['published_at'];
}
