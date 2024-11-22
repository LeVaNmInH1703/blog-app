<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_filename',
        'last_activity_at',
        'google_id',
        'email_verified_at',
        'birth_day',
        'country',
        'education',
        'gender',
        'token_verify_email'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function sendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id1', 'user_id2');
    }
    public function receiveRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id2', 'user_id1');
    }
    public function friends()
    {
        return $this->sendRequests()->whereIn('user_id2', $this->receiveRequests()->pluck('user_id1'));
    }
    public function sendRequestWithoutReceive()
    {
        return $this->sendRequests()->whereNotIn('user_id2', $this->receiveRequests()->pluck('user_id1'));
    }
    public function receiveWithoutSendRequest()
    {
        return $this->receiveRequests()->whereNotIn('user_id1', $this->sendRequests()->pluck('user_id2'));
    }
    public function chats()
    {
        return $this->belongsToMany(
            Chat::class,
            'chat_users',
            'user_id',
            'chat_id'
        )
            ->withPivot('role');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id');
    }
    // public function notifications(){
    //     return $this->hasMany(Notification::class, 'user_id_receive');

    // }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user')->withPivot('read')->withTimestamps();
    }
    // public function getTempAttribute(){
    //     //Accessor test
    //     // user->temp=return ...;
    //     return "{$this->name} {$this->email}";
    // }
    // public function setNameAttribute($value)
    // {
    //     // test Mutator
    //     // cấu hình cách mà Name được lưu
    //     // $user = User::create([
    //     //     'first_name' => 'JOHN',
    //     //     'last_name' => 'DOE',
    //     // ]);
    //     $this->attributes['name'] = $value;// ví dụ strtolower($value);
    // }
}
