<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Stichoza\GoogleTranslate\GoogleTranslate;
class BlogComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public $blog,$emojis;
    public function __construct($blog,$emojis=null)
    {
       $this->blog=$blog;
       $this->emojis=$emojis;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.blog-component');
    }
}
