<?php

namespace App\Services;

use App\Repositories\EmojiRepository;

class EmojiService
{
    protected $emojiRepository;
    protected $commentService;

    public function __construct(EmojiRepository $emojiRepository, CommentService $commentService)
    {
        $this->emojiRepository = $emojiRepository;
        $this->commentService = $commentService;
    }
    public function toggle($modelId, $modelName, $emojiName)
    {
        $existingEmoji = $this->emojiRepository->existingEmoji(
            $modelId,
            $modelName,
        );
        $temp=$existingEmoji;
        if ($existingEmoji) $this->emojiRepository->deleteExistingEmoji($existingEmoji);

        if (($temp && $temp->name != $emojiName) || !$temp) {
            $this->emojiRepository->create($modelId, $modelName, $emojiName);
        }
    }
}
