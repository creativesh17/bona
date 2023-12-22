<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// php artisan make:controller Admin/PostController --model=Post

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'body', 'image', 'view_count', 'status', 'is_approved',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function categories() {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
