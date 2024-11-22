<?php

namespace App\Services;

use App\Repositories\ChatUserRepository;

class ChatUserService
{
    protected $chatUserRepository;
    protected $commentService;

    public function __construct(ChatUserRepository $chatUserRepository)
    {
        $this->chatUserRepository = $chatUserRepository;
    }
    public function createChatUser($chatId,$userId,$role)
    {
        return $this->chatUserRepository->createChatUser($chatId,$userId,$role);
    }
    public function getOtherUser($chatId){
        return $this->chatUserRepository->getOtherUser($chatId);
    }
    public function getRole($chatId,$userId){
        return $this->chatUserRepository->getRole($chatId,$userId);
    }
    public function removeMemberInGroup($userId, $chatId)
    {
        return $this->chatUserRepository->removeMemberInGroup($userId,$chatId);
    }
}
