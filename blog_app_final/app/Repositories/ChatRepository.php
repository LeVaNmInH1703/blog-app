<?php

namespace App\Repositories;

use App\Models\Chat;

class ChatRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Chat::class);
    }
    public function createChatPrivate($avatarFilename, $userId1, $userId2)
    {
        $chatType = 'private';
        $name = $this->createNameGroup($userId1, $userId2);
        return Chat::create([
            'name' => $name,
            'avatar_filename' => $avatarFilename,
            'chat_type' => $chatType,
        ]);
    }
    public function createChatGroup($avatarFilename, $name)
    {
        $chatType = 'public';
        return Chat::create([
            'name' => $name,
            'avatar_filename' => $avatarFilename,
            'chat_type' => $chatType,
        ]);
    }
    public function createNameGroup($userId1, $userId2)
    {

        return ($userId1 + $userId2) ."". ($userId1 * $userId2);
    }
    public function getChatPrivate($userId1, $userId2)
    {   
        $name = $this->createNameGroup($userId1, $userId2);

        return Chat::where([['chat_type', 'private'], ['name', $name]])->first();
    }
    public function isInChat($userId,$chatId){
        return Chat::find($chatId)->users->contains('user_id',$userId);
    }
    public function find($chatId){
        return Chat::find($chatId);
    }
    
    
}
