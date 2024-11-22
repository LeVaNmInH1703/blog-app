<?php

use App\Services\TranslateService;

if (!function_exists('getTranslateByKey')) {
    function getTranslateByKey($key)
    {
        return TranslateService::getTranslateByKey($key);
    }
}
if (!function_exists('hashToTranslate')) {
    function hashToTranslate($text)
    {
        return TranslateService::hashToTranslate($text);
    }
}

if (!function_exists('translate')) {
    function translate($originalText, $language = 'en')
    {
        TranslateService::getTranslation($originalText, $language);
    }
}
