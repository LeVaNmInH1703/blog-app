<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastMessages extends Model
{
    use HasFactory;
    protected $fillable = ['last_message_id','user_id','group_id'];
}
