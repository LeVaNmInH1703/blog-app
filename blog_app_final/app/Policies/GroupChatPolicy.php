<?php

namespace App\Policies;

use App\Http\Controllers\ChatController;
use App\Models\GroupChat;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GroupChatPolicy
{
    public $GROUPCHATCONTROLLER;
    public function __construct(){
        $this->GROUPCHATCONTROLLER = app('App\Http\Controllers\GroupChatController');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user,GroupChat $groupChat): bool
    {
        return $this->GROUPCHATCONTROLLER->isInGroup($user, $groupChat);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user,GroupChat $groupChat): bool
    {
        return $this->GROUPCHATCONTROLLER->isAdmin($groupChat, $user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GroupChat $groupChat): bool
    {
        return $this->GROUPCHATCONTROLLER->isAdmin($groupChat, $user);
    }
}
