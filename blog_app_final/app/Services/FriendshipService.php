<?php

namespace App\Services;

use App\Jobs\Translate;
use App\Repositories\FriendshipRepository;

class FriendshipService
{
    protected $friendshipRepository;
    protected $commentService;

    public function __construct(FriendshipRepository $friendshipRepository, CommentService $commentService)
    {
        $this->friendshipRepository = $friendshipRepository;
        $this->commentService = $commentService;
    }
    public function createFriendship($user_id1, $user_id2)
    {
        return $this->friendshipRepository->createFriendship($user_id1, $user_id2);
    }
    public function deleteFriendShip($user_id1, $user_id2)
    {
        return $this->friendshipRepository->deleteFriendShip($user_id1, $user_id2);
    }
}
