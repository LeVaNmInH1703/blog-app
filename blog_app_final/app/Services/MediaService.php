<?php

namespace App\Services;

use App\Repositories\MediaRepository;


use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class MediaService
{
    public $mediaRepository;
    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }
    public function saveFilesWithModel($files, $model, $modelName)
    {
        if (!$files)  return;
        foreach ($files as $file) {
            $fileType = $file->getMimeType(); // Thay thế dấu '/' bằng dấu '_'
            $fileName = $modelName . '_' . str_replace('/', '_', $fileType) . '_' . $model->id . Str::random(20) . '.' . $file->extension();
            $filePath = '';
            if (strpos($fileType, 'image') !== false) {
                $filePath = 'images';  // Nếu là hình ảnh, gán filePath là 'image'
            } elseif (strpos($fileType, 'video') !== false) {
                $filePath = 'videos';  // Nếu là video, gán filePath là 'video'
            } else {
                $filePath = 'files';  // Nếu không phải hình ảnh hay video, gán filePath là 'files'
            }
            $oldName = $file->getClientOriginalName();
            //tạo bản ghi trong csdl
            $this->mediaRepository->createWithModel(
                $model,
                $fileName,
                $fileType,
                $filePath,
                '',
                $oldName
            );
            //lưu file vào thư mục public
            $file->move(public_path($filePath), $fileName);
        }
    }
    public function resizeAndSaveImage($name, $image, $width = 200)
    {
        $ratioWidthHeight = getimagesize($image)[0] / getimagesize($image)[1];
        // resize
        $manager = new ImageManager(new Driver());
        $manager->read($image)->resize($width, $width / $ratioWidthHeight)
            ->save(public_path('images_resize') . '/' . $name);
        //move
        $image->move(public_path('images'), $name);
    }
}
