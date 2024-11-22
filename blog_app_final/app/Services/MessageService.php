<?php

namespace App\Services;

use App\Jobs\Translate;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class MessageService
{
    protected $messageRepository, $mediaService;

    public function __construct(MessageRepository $messageRepository, MediaService $mediaService)
    {
        $this->messageRepository = $messageRepository;
        $this->mediaService = $mediaService;
    }
    public function getMessagesInChat($chatId)
    {
        $messages = $this->messageRepository->getMessagesInChat($chatId);
        return $messages;
    }
    public function createMessage($request)
    {

        $userId = Auth::id();
        $chatId = session('chatCurrent');
        // dd($userId, $chatId,$request->message);
        $newMessage = $this->messageRepository->createMessage($userId, $chatId, $request->message);
        $this->mediaService->saveFilesWithModel($request->file('mediaFiles'), $newMessage,'message');
        $this->mediaService->saveFilesWithModel($request->file('otherFiles'), $newMessage,'message');

        return $newMessage;
    }
    public function getMessageById($id){
        $message = $this->messageRepository->getMessageById($id);
        return $message;
    }
    
}
