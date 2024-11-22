<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageRepository extends BaseRepository
{
    public $commentRepository;
    public $emojiBlogDetailRepository;
    public function __construct()
    {
        parent::__construct(Message::class);
    }
    public function getMessagesInChat($chatId)
    {
        return $this->getMessageBase()->where('chat_id', $chatId)
            ->limit(50) // Lấy tối đa 50 tin nhắn
            ->paginate(50, ['*'], 'messagePage');
    }
    public function getMessageBase()
    {
        return Message::with('medias')
            ->withCount('medias')
            ->orderBy('created_at', 'desc');
    }
    public function getMessageById($id){
        return $this->getMessageBase()->where('id', $id)->first();
    }
    public function createMessage($userId, $chatId, $content)
    {
        $message = Message::create([
            'user_id' => $userId,
            'chat_id' => $chatId,
            'content' => $content,
        ]);
        return $message;
    }
}
