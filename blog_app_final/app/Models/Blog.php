<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    use HasFactory;
    protected $guarded  = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function medias()
    {
        return $this->morphMany(Media::class, 'model');
    }
    public function emojis()
    {
        return $this->morphMany(Emoji::class, 'model');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'blog_id');
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
    // Lấy một emoji cụ thể của user trong blog (nếu có)
    public function myEmoji()
    {
        return $this->morphOne(Emoji::class, 'model') // Đảm bảo quan hệ morph được thiết lập đúng
            ->where('user_id', Auth::id()); // Lọc theo user đang đăng nhập
    }

    // public function emojis()
    // {
    //     return $this->belongsToMany(Emoji::class, 'emoji_blog_details', 'blog_id', 'emoji_id')->withPivot('user_id') // Include user_id from the pivot table
    //         ->join('users', 'emoji_blog_details.user_id', '=', 'users.id') // Join with users table
    //         ->select('emojis.*', 'users.name as userName', 'users.id as userId');
    // }
}
