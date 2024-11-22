<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
    public function getFriends()
    {

        $friends = Auth::user()->friends;
        return $friends;
    }
    public function getUsersMayKnow()
    {
        $usersMayKnow = User::whereNotIn('id', Auth::user()->sendRequests->pluck('id')->toArray())
            ->whereNotIn('id', Auth::user()->receiveRequests->pluck('id')->toArray())
            ->where('id', '!=', Auth::id())->get();
        return $usersMayKnow;
    }
    public function getUser($userId)
    {
        return User::where('id', $userId)->first();
    }
    public function updateAuthName($nameName)
    {
        User::find(Auth::id())->update([
            'name' => $nameName
        ]);
    }
    public function updateAuthAvatar($newFileName) {
        User::find(Auth::id())->update([
            'avatar_filename' => $newFileName,
        ]);
    }
}
