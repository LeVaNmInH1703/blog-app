<?php

namespace App\View\Components;

use App\Models\FriendShips;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class CardInfoUserComponent extends Component
{
    public $user, $isSender, $isReceiver, $isFriend, $isMayKnow;
    public function __construct($user)
    {
        $commonFriends = User::whereIn('id', $user->friends->pluck('id'))
                ->whereIn('id', Auth::user()->friends->pluck('id'));
        if(Auth::user()->receiveWithoutSendRequest->contains($user)){
            $this->isSender=true;
            $user->timeAgo = strtr(FriendShips::where([['user_id1', $user->id], ['user_id2', Auth::id()]])->first()->created_at->diffForHumans(now()), ['before' => 'ago']);
        }else if(Auth::user()->sendRequestWithoutReceive->contains($user)){
            $this->isReceiver=true;
            $user->timeAgo = strtr(FriendShips::where([['user_id2', $user->id], ['user_id1', Auth::id()]])->first()->created_at->diffForHumans(now()), ['before' => 'ago']);
        }else if(Auth::user()->friends->contains($user)){
            $this->isFriend=true;
        }else{
            $this->isMayKnow=true;
        }
        if(!$this->isFriend){
            $user->commonFriends = $commonFriends->take(3)->get();
            $user->commonFriendsCount = $commonFriends->count();
        }
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-info-user-component', [
            'user' => $this->user,
            'isSender' => $this->isSender,
            'isReceiver' => $this->isReceiver,
            'isFriend' => $this->isFriend,
            'isMayKnow' => $this->isMayKnow,
        ]);
    }
}
