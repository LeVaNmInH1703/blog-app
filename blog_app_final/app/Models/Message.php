<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $guarded =[];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }
    public function medias()
    {
        return $this->morphMany(Media::class, 'model');
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
