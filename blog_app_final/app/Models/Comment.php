<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;
    protected $guarded  = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function blog(){
        return $this->belongsTo(Blog::class);
    }
    public function medias()
    {
        return $this->morphMany(Media::class, 'model');
    }
    public function emojis()
    {
        return $this->morphMany(Emoji::class, 'model');
    }
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Quan hệ với các bình luận con
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
    public function myEmoji()
    {
        return $this->morphOne(Emoji::class, 'model') // Đảm bảo quan hệ morph được thiết lập đúng
            ->where('user_id', Auth::id()); // Lọc theo user đang đăng nhập
    }
    // public function emojis(){
    //     // return $this->belongsToMany(Emoji::class,'emoji_comment_details');
    //     return $this->belongsToMany(Emoji::class, 'emoji_comment_details', 'comment_id', 'emoji_id')->withPivot('user_id') // Include user_id from the pivot table
    //         ->join('users', 'emoji_comment_details.user_id', '=', 'users.id') // Join with users table
    //         ->select('emojis.*', 'users.name as userName','users.id as userId');
    // }
    // public function comments(){
    //     return $this->belongsToMany(Comment::class,'reply_comment_details','comment_id','reply_comment_id');
    // }
    // public function replyCommentDetail(){
    //     return $this->belongsTo(ReplyCommentDetail::class,'id','reply_comment_id');
    // }
    // public function image(){
    //     return $this->belongsTo(ImageComment::class,'id','comment_id');
    // }
}
