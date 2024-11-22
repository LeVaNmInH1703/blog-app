<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $guarded  = [];
    protected $casts = [
        'last_activity_at' => 'datetime',
    ];
    public $timestamps = true;
    public function users(){
        return $this->belongsToMany(User::class,'chat_users','chat_id','user_id')->withPivot('role');
    }
    public function messages(){
        return $this->hasMany(Message::class,'chat_id');
    }
}
