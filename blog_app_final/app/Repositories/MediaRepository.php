<?php

namespace App\Repositories;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class MediaRepository extends BaseRepository
{
    public $commentRepository;
    public $emojiBlogDetailRepository;
    public function __construct()
    {
        parent::__construct(Media::class);
    }
    public function modelType($modelName)
    {
        // Tạo tên đầy đủ của model từ tên đơn
        return "App\Models\\" . ucfirst($modelName);
    }
    public function createWithModel($model, $fileName,$fileType,$filePath,$description,$oldName){
        $model->medias()->create([
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_path' => $filePath,
            'old_name' => $oldName,
            'description' => $description,
        ]);
    }
}
