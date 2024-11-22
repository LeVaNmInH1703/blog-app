<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateService
{

    public static function getTranslation($originalText, $language = 'en')
    {
        //tạo key
        $key = hashToTranslate($originalText);
        $cacheKey = 'translation_' . $key . '_' . $language;
        //lấy trong cache
        $translatedText = Cache::get($cacheKey);
        if ($translatedText) return $translatedText;
        // Tìm bản dịch trong bảng translations theo key
        $translation = Translation::where('key', $key)->first();
        // nếu có trả về
        if (($translation && $translation->$language)) return $translation->$language;
        // dịch mới
        $tr = new GoogleTranslate($language);
        $translatedText = $tr->translate($originalText);
        $original_language = $tr->getLastDetectedSource();
        // nếu chưa có translation tạo mới
        if (!$translation) {
            $translation = new Translation();
            $translation->key = $key;
            $translation->original = $originalText;
            $translation->original_language = $original_language;
        }

        $translation->$language = $translatedText;
        Cache::put($cacheKey, $translation->{$language}, 180);
        $translation->save();
        Log::info($translatedText);
        return $translatedText;
    }
    public static function hashToTranslate($text)
    {
        return hash('sha256', $text);
    }
    public static function getTranslateByKey($key)
    {
        try {
            // Tìm bản dịch trong bảng translations theo key
            $translation = Translation::where('key', $key)->first();
            // nếu có trả về
            $language = session('locale');
            return ['translatedText' => $translation->$language, 'originalLanguage' => $translation->original_language];
        } catch (\Exception $e) {
            return null;
        }
    }
}
