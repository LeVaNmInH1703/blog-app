<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FriendshipService;
use App\View\Components\CardInfoUserComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendShipController extends Controller
{
    public $friendshipService;
    public function __construct(FriendshipService $friendshipService){
        $this->friendshipService = $friendshipService;
    }
    
    public function addFriend(Request $request, User $user)
    {
        if (!$user) abort(403);
        if ($user->id != Auth::id() && !Auth::user()->friends->contains('id', $user->id)) {
            $this->friendshipService->createFriendShip(Auth::id(), $user->id);
        }
        return response(['cardUserOuterHTML'=>str((new CardInfoUserComponent($user))->render())]);
    }
    public function acceptRequest(Request $request, User $user)
    {
        if (!$user) abort(403);
        if ($user->id != Auth::id()) {
            if (Auth::user()->receiveRequests->contains('id', $user->id)) {
                $this->friendshipService->createFriendShip(Auth::id(), $user->id);
            } else
                return redirect()->back()->with('message', __("public.This request has been cancelled"));
        }
        return response(['cardUserOuterHTML'=>str((new CardInfoUserComponent($user))->render())]);
    }
    public function cancelRequest(Request $request, User $user)
    {
        if (!$user) abort(403);
        if ($user->id != Auth::id() && Auth::user()->sendRequests->contains('id', $user->id)) {
            $this->friendshipService->deleteFriendShip(Auth::id(), $user->id);
        }
        return response(['cardUserOuterHTML'=>str((new CardInfoUserComponent($user))->render())]);
    }
    public function unfriend(Request $request, User $user)
    {
        if (!$user) abort(403);
        if ($user->id != Auth::id() && Auth::user()->friends->contains('id', $user->id)) {
            $this->friendshipService->deleteFriendShip(Auth::id(), $user->id);
            $this->friendshipService->deleteFriendShip($user->id, Auth::id());
        }
        return response(['cardUserOuterHTML'=>str((new CardInfoUserComponent($user))->render())]);

    }
    // public function setGroupAsUser(&$group, $otherUser = null)
    // {
    //     if (!$otherUser)
    //         $otherUser = $group->users->firstWhere('id', '!=', Auth::id());
    //     $group->avatar_filename = $otherUser->avatar_filename;
    //     $group->name = $otherUser->name;
    //     $group->last_activity_at = $otherUser->last_activity_at;
    //     $group->user_id = $otherUser->id;
    // }
    // public function getGroups()
    // {
    //     return Auth::user()->groups->filter(function ($group) {
    //         if ($group->isChatWithSomeone) {
    //             $otherUser = $group->users->firstWhere('id', '!=', Auth::id());
    //             $this->setGroupAsUser($group, $otherUser);
    //             return Auth::user()->friends->contains('id', $otherUser->id);
    //         }
    //         return true;
    //     })->map(function ($group) {
    //         $newestMessage = $this->newestMessageInGroup($group->id);
    //         $group->latest_message_time = $newestMessage ? $newestMessage->created_at : null;
    //         return $group;
    //     })->sortByDesc('latest_message_time');
    // }
    // public function newestMessageInGroup($group_id)
    // {
    //     return Message::where([
    //         ['chat_id', $group_id],
    //     ])->latest()->first();
    // }
    // public function getFriend()
    // {
    //     return Auth::user()->friends->map(function ($friend) {
    //         $nameGroup = $this->createNameGroup($friend, Auth::user());
    //         $group = Chat::where([['name', $nameGroup]])->first();
    //         $newestMessage = null;
    //         if ($group)
    //             $newestMessage = $this->newestMessageInGroup($group->id);
    //         $friend->latest_message_time = $newestMessage ? $newestMessage->created_at : null;
    //         return $friend;
    //     })->sortByDesc('latest_message_time');
    // }
    // public function createNameGroup($user1, $user2)
    // {
    //     return ($user1->id + $user2->id) . ($user1->id * $user2->id);
    // }
}
