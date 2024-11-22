<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    // Quan hệ polymorphic với mô hình khác (Post, Comment, Message)
    public function notifiable()
    {
        return $this->morphTo();
    }
    // Quan hệ với bảng users (người nhận thông báo)
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')->withPivot('read')->withTimestamps();
    }
}
