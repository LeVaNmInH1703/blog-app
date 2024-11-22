<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class Translate implements ShouldQueue
{
    use Queueable;
    public $content,$language;
    public function __construct($content,$language)
    {
        $this->content = $content;
        $this->language = $language;
    }
    public function handle(): void
    {
        translate($this->content,$this->language);
    }
}
