<?php

namespace App\Http\Controllers;

use App\Events\HasNewMessageEvent;
use App\Models\Chat;
use App\Services\ChatService;
use App\Services\MediaService;
use App\Services\MessageService;
use App\View\Components\ChatItemComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public $chatService, $mediaService, $messageService;
    public function __construct(
        ChatService $chatService,
        MediaService $mediaService,
        MessageService $messageService
    ) {
        $this->chatService = $chatService;
        $this->mediaService = $mediaService;
        $this->messageService = $messageService;
    }
    // public function countGroupHasNewMessage()
    // {
    //     $result = 0;
    //     foreach (Auth::user()->groups as $group)
    //         // dd(($group->aaaa));
    //         if ($group->pivot->role_id != 4 && $this->checkNewMessage($group->id, Auth::id()))
    //             $result++;
    //     return $result;
    // }
    public function message()
    {
        // dd(Auth::user()->chats);
        // $groups = $this->chatService->getGroups();
        // $friends = $this->getFriend();
        // foreach ($groups as $group) {
        //     $group->timeAgo = strtr(now()->subSeconds(strtotime(now()) - strtotime($group->last_activity_at))->diffForHumans(), ['before' => 'ago']);
        //     $group->hasNewMessage = $group->pivot->role_id != 4 && $this->checkNewMessage($group->id, Auth::id());
        // }
        // $newGroup = session('newGroup');
        // if ($newGroup) {
        //     session(['chatCurrent' => $newGroup->getId()]);
        // }
        // $group = GroupChat::find(session('chatCurrent'));
        // if (
        //     $group &&
        //     $group->isChatWithSomeone &&
        //     !$this->isFriendWithAnotherInGroup($group)
        // )
        //     session()->forget('chatCurrent');
        $chats = $this->chatService->setChatsPrivateAsUser(Auth::user()->chats);
        // dd($chats);
        return view("pages.message");
    }
    // public function takeLastMessage($group_id, $user_id)
    // {
    //     return LastMessages::where([
    //         ['group_id', $group_id],
    //         ['user_id', $user_id]
    //     ]);
    // }
    // public function checkNewMessage($group_id, $user_id)
    // {
    //     $latestMessage = $this->getLatestMessageInGroup($group_id);
    //     $latestMessageOfAuth = $this->takeLastMessage($group_id, $user_id)->first();
    //     if ($latestMessage == null)
    //         return false;
    //     return $latestMessage->id > ($latestMessageOfAuth->last_message_id ? $latestMessageOfAuth->last_message_id : -1);
    // }
    // public function getLatestMessageInGroup($group_id)
    // {
    //     return Message::where([['chat_id', $group_id]])->latest()->first();
    // }
    // public function getMessageInGroup($group_id)
    // {
    //     return Message::where([['chat_id', $group_id]]);
    // }
    // public function updateSeenMessageInGroup($group_id)
    // {
    //     $group = GroupChat::find($group_id);
    //     if (!$group || !$this->GROUPCONTROLLER->isInGroup(User::find(Auth::id()), $group))
    //         return abort(404);
    //     return $this->useTransaction(function () use ($group) {
    //         $latestMessage = $this->getLatestMessageInGroup($group->id);
    //         $this->takeLastMessage($group->id, Auth::id())->update([
    //             'last_message_id' => $latestMessage ? $latestMessage->id : null,
    //         ]);
    //     });
    // }
    // public function isShowTimeOfMessage($previousMessage, $message)
    // {
    //     return $previousMessage->user_id != $message->user_id || $previousMessage->created_at->diffInSeconds($message->created_at) > 60 * 3;
    // }
    public function chatHistory(Chat $chat)
    {
        if (!$chat || !$chat->users->contains('id', Auth::id())) {
            return abort(404);
        }
        if ($chat->chat_type == 'private') {
            $this->chatService->setChatAsUser($chat);
        }
        // dd($chat->id);
        session(['chatCurrent' => $chat->id]);
        $this->chatService->getMessageInChat($chat->id);
        $chat->messages = $this->chatService->getMessageInChat($chat->id);
        return [
            'view' => view('components.chat-history-component', compact('chat'))->render(),
        ];
    }
    // /**
    //  * check 2 người trong group riêng có phải bạn không
    //  * @param \App\Models\GroupChat $group
    //  * @return bool
    //  */
    // public function isFriendWithAnotherInGroup(GroupChat $group)
    // {
    //     return Auth::user()->friends->contains('id', $group->users->firstWhere('id', '!=', Auth::id())->id);
    // }
    // private function scanForVirus($filePath)
    // {
    //     return 'OK';
    // }
    // private function isHasVirus($files)
    // {
    //     $isVirus = false;
    //     if ($files)
    //         foreach ($files as $file)
    //             $isVirus |= $this->scanForVirus($file->getRealPath()) != 'OK';

    //     return $isVirus;
    // }
    public function sendMessage(Request $request)
    {
        if ($request->message == "" && $request->mediaFiles == '' && $request->otherFiles == '')
            return;
        $newMessage = $this->messageService->createMessage($request);

        if ($newMessage->id)
            event(new HasNewMessageEvent($newMessage));

        if ($newMessage->chat->chat_type == 'private')
            $newMessage->chat->update([
                'last_activity_at' => now(),
            ]);
        return response()->json([
            'status' => 200
        ]);
    }
    // public function updateLastActivity($request)
    // {
    //     return $this->useTransaction(function () use ($request) {
    //         GroupChat::find($request->chat_id)->update([
    //             'last_activity_at' => now()
    //         ]);
    //     });
    // }
    public function getChatItemPatialView(Request $request, $messageId)
    {
        $message=$this->messageService->getMessageById($messageId);
        return (new ChatItemComponent($message))->render();
    }
    // public function createMessage(Request $request, &$newMessage)
    // {
    //     return $this->useTransaction(function () use ($request, &$newMessage) {
    //         $newMessage = Message::create([
    //             'user_id' => Auth::id(),
    //             'chat_id' => session('chatCurrent'),
    //             'content' => $request->message
    //         ]);
    //         $files = $request->file('otherFiles');
    //         if ($files)
    //             foreach ($files as $file) {
    //                 if (!$file) continue;
    //                 $fileMessageName = 'message_file_' . $newMessage->id . Str::random(20) . '.' . $file->extension();
    //                 $file->move(public_path('files'), $fileMessageName);
    //                 FileMessage::create([
    //                     'file_name' => $fileMessageName,
    //                     'message_id' => $newMessage->id,
    //                     'old_name' => $file->getClientOriginalName()
    //                 ]);
    //             }

    //         $files = $request->file('mediaFiles');
    //         if ($files)
    //             foreach ($files as $file) {
    //                 $mimeType = $file->getMimeType();
    //                 if (!$file) continue;
    //                 if (str_starts_with($mimeType, 'image/')) {
    //                     $fileMessageName = 'message_image_' . $newMessage->id . Str::random(20) . '.' . $file->extension();

    //                     // resize chú ý dùng đúng driver
    //                     $manager = new ImageManager(new Driver());
    //                     $temp = $manager->read($file);
    //                     if ($temp->width() > 300)
    //                         $temp->resize(300, 300 * $temp->height() / $temp->width());
    //                     $temp->save(public_path('images_resize') . '/' . $fileMessageName);

    //                     //move
    //                     $file->move(public_path('images'), $fileMessageName);
    //                     FileMessage::create([
    //                         'file_name' => $fileMessageName,
    //                         'message_id' => $newMessage->id,
    //                         'old_name' => $file->getClientOriginalName()
    //                     ]);
    //                 } else if (str_starts_with($mimeType, 'video/')) {
    //                     $fileMessageName = 'message_video_' . $newMessage->id . Str::random(20) . '.' . $file->extension();
    //                     $file->move(public_path('videos'), $fileMessageName);
    //                     FileMessage::create([
    //                         'file_name' => $fileMessageName,
    //                         'message_id' => $newMessage->id,
    //                         'old_name' => $file->getClientOriginalName()
    //                     ]);
    //                 }
    //             }
    //     });
    // }
}
