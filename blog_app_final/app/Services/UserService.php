<?php

namespace App\Services;

use App\Jobs\Translate;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class UserService
{
    protected $userRepository, $mediaService;

    public function __construct(UserRepository $userRepository, MediaService $mediaService)
    {
        $this->userRepository = $userRepository;
        $this->mediaService = $mediaService;
    }
    public function getFriends()
    {
        $friends = $this->userRepository->getFriends();
        return $friends;
    }
    public function getUsersMayKnow()
    {
        $usersMayKnow = $this->userRepository->getUsersMayKnow();
        return $usersMayKnow;
    }
    public function getUser($user_id)
    {
        $user = $this->userRepository->getUser($user_id);
        return $user;
    }
    public function updateAuthName($newName)
    {
        $this->userRepository->updateAuthName($newName);
    }
    public function updateAuthAvatar($newImage)
    {
        $avatarFileName = basename(parse_url(Auth::user()->avatar_filename, PHP_URL_PATH)); // Lấy tên file từ đường dẫn

        if (strpos($avatarFileName, 'avatar') === 0) { //nếu là avatar gốc
            // $this->mediaService->saveFilesWithModel($newImage,Auth::user(),'user');
            $avatarFileName = 'avatar_' . Auth::id() . Str::random(20) . $newImage->extension();
            $this->userRepository->updateAuthAvatar($avatarFileName);
        }
        $this->mediaService->resizeAndSaveImage($avatarFileName, $newImage);
    }
}
