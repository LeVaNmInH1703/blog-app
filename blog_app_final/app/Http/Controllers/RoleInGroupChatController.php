<?php

namespace App\Http\Controllers;

use App\Models\RoleInGroupChat;
use Illuminate\Http\Request;

class RoleInGroupChatController extends Controller
{
    public function checkAndMake()
    {
        $this->makeRole('admin');
        $this->makeRole('member');
    }
    public function makeRole($name)
    {
        if (!RoleInGroupChat::where('name', $name)->exists())
            return $this->useTransaction(function () use ($name) {
                RoleInGroupChat::create([
                    'name' => $name
                ]);
            });
    }
    
}
