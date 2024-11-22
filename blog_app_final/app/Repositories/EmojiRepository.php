<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\Emoji;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class EmojiRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Emoji::class);
    }
    public function create($modelId, $modelName, $emojiName)
    {
        // Lấy tên đầy đủ của model
        $modelType = app($this->modelType($modelName));

        // Kiểm tra model có tồn tại không
        $model = $modelType::find($modelId);
        if (!$model) {
            // Nếu model không tồn tại, có thể trả về lỗi hoặc thông báo
            return false;
        }

        // Tạo emoji cho model, Laravel sẽ tự động điền model_type và model_id
        $emoji = $model->emojis()->create([
            'name' => $emojiName,
            'user_id' => Auth::id(),
        ]);

        return  $emoji ? true : false;
    }

    public function modelType($modelName)
    {
        // Tạo tên đầy đủ của model từ tên đơn
        return "App\Models\\" . ucfirst($modelName);
    }

    public function delete($modelId, $modelName)
    {
        $modelType = $this->modelType($modelName);
        $existingEmoji = $this->existingEmoji($modelId, $modelType);
        if ($existingEmoji)
            $this->deleteExistingEmoji($existingEmoji);
    }
    public function deleteExistingEmoji(Emoji $existingEmoji)
    {
        return $existingEmoji->delete();
    }
    public function existingEmoji($modelId, $modelType)
    {
        if (!str_starts_with($modelType, "App\\Models\\"))
            $modelType = $this->modelType($modelType);
        return  Emoji::where([
            ['model_type', $modelType],
            ['model_id', $modelId],
            ['user_id', Auth::id()]
        ])->first();
    }
}
