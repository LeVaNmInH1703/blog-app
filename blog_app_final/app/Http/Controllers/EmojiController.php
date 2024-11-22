<?php

namespace App\Http\Controllers;

use App\Models\Emoji;
use App\Services\BlogService;
use App\Services\CommentService;
use App\Services\EmojiService;
use Illuminate\Http\Request;

class EmojiController extends Controller
{
    public $emojiService;
    public function __construct(EmojiService $emojiService)
    {
        $this->emojiService = $emojiService;
    }
    public function store(Request $request)
    {
        $modelId = $request->input('model_id');
        $modelName = $request->input('model_name');
        $emojiName = $request->input('emoji_name');
        $this->emojiService->toggle($modelId, $modelName, $emojiName);
        $modelService = app("App\Services\\" . ucfirst($modelName) . "Service");
        return response()->json([
            'message' => 'successfully',
            'countEmojiInnerHTML' => view(
                'components.count-emoji-component',
                [
                    'emojis' => $modelService->getWithEmojis($modelId)->emojis,
                    'emojis_count' => $modelService->getEmojisCount($modelId)
                ]
            )->render(),
            'btnLikeTextInnerHTML' => view(
                'components.button-like-component',
                ['myEmoji' => $modelService->getMyEmoji($modelId)]
            )->render()
        ], 200);
    }
}
