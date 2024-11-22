<?php

namespace App\Repositories;

use App\Models\Friendships;

class FriendshipRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Friendships::class);
    }
    public function createFriendship($user_id1, $user_id2)
    {
        return FriendShips::create([
                "user_id1" => $user_id1,
                "user_id2" => $user_id2,
            ]);
    }
    public function deleteFriendShip($user_id1, $user_id2)
    {
        return FriendShips::where([["user_id1", $user_id1], ['user_id2', $user_id2]])->delete();
    }
}
