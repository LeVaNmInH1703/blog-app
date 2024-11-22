<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{

    public function download($fileName,$oldName)
    {
        $filePath = public_path('files/' . $fileName); // Đường dẫn tới tệp cần tải xuống

        // Kiểm tra xem tệp có tồn tại không
        if (!file_exists($filePath)) {
            return abort(404); // Trả về lỗi 404 nếu tệp không tồn tại
        }

        // Trả về tệp để tải xuống
        return response()->download($filePath,$oldName);
    }
}
