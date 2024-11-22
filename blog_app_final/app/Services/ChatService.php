<?php

namespace App\Services;

use App\Repositories\ChatRepository;

class ChatService
{
    protected $chatRepository, $userService, $chatUserService, $messageService;

    public function __construct(
        ChatRepository $chatRepository,
        MessageService $messageService,
        ChatUserService $chatUserService,
        UserService $userService
    ) {
        $this->chatRepository = $chatRepository;
        $this->chatUserService = $chatUserService;
        $this->userService = $userService;
        $this->messageService = $messageService;
    }
    public function createChatPrivate($userId1, $userId2, $avatarFilename = 'groupDefault.png')
    {
        return $this->chatRepository->createChatPrivate($avatarFilename, $userId1, $userId2);
    }
    public function createChatGroup($avatarFilename, $name)
    {
        $this->chatRepository->createChatGroup($avatarFilename, $name);
    }
    public function getChatPrivate($userId1, $userId2)
    {
        $chat = $this->chatRepository->getChatPrivate($userId1, $userId2);

        if (!$chat) {
            $chat = $this->createChatPrivate($userId1, $userId2);
            $this->chatUserService->createChatUser($chat->id, $userId1, 'admin');
            $this->chatUserService->createChatUser($chat->id, $userId2, 'admin');
        }

        return $chat;
    }
    public function setChatsPrivateAsUser($chats)
    {
        foreach ($chats as $chat) {
            if ($chat->chat_type == 'private') {
                $this->setChatAsUser($chat);
            }
        }
        return $chats;
    }
    public function setChatAsUser(&$chat)
    {
        $chatUser = $this->chatUserService->getOtherUser($chat->id);
        $user = $this->userService->getUser($chatUser->user_id);
        foreach ($user->getAttributes() as $key => $value) {
            if ($key == 'id')
                $key = 'userId';
            $chat->$key = $value; // Ghi đè nếu $key đã tồn tại trong $model1
            // dump($key, $value);
        }
    }
    public function getMessageInChat($chatId)
    {
        return $this->messageService->getMessagesInChat($chatId);
    }
    public function isInChat($userId,$chatId){
        return $this->chatRepository->isInChat($userId, $chatId);
    }
    public function find($chatId){
        return $this->chatRepository->find($chatId);
    }
}
