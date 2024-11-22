<?php
// composer dump-autoload  : update composer
// Hàm kiểm tra email hợp lệ
if (! function_exists('is_valid_email')) {
    function is_valid_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

// Hàm tạo slug từ chuỗi
if (! function_exists('create_slug')) {
    function create_slug($string)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return strtolower($slug);
    }
}

if (! function_exists('handleUrl')) {
    function handleUrl($url)
    {
        $newUrl = filter_var($url, FILTER_VALIDATE_URL)
                            ? $url
                            : asset('images/' . $url);
        return $newUrl;
    }
}
