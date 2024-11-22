<?php

namespace App\Repositories;

use App\Models\ChatUser;
use Illuminate\Support\Facades\Auth;

class ChatUserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(ChatUser::class);
    }
    public function createChatUser($chatId, $userId, $role)
    {

        return ChatUser::create([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'role' => $role
        ]);
    }
    public function getOtherUser($chatId)
    {
        return ChatUser::where([
            ['chat_id', $chatId],
            ['user_id', '!=', Auth::id()]
        ])->first();
    }
    public function getRole($chatId, $userId)
    {
        return ChatUser::where([['chat_id', $chatId], ['user_id', $userId]])->first()->role;
    }
    public function removeMemberInGroup($userId, $chatId)
    {
        return
            ChatUser::where([["user_id", $userId], ['chat_id', $chatId]])->delete();
    }
}
