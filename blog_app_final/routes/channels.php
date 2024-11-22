<?php

use App\Models\GroupChat;
use App\Models\GroupChatDetail;
use App\Models\RoleInGroupChat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('channelReloadUsersPage.{id}', function ($user, $id) {
    return  $user->id == $id;
});
Broadcast::channel('channelHasNewMessage.{chat_id}', function ($user,$chat_id) {
    return  $user->chats->contains('id', $chat_id);
});
Broadcast::channel('channelHasNewNotification.{user_id}', function ($user,$user_id) {
    return (int) $user->id == (int) $user_id;
});