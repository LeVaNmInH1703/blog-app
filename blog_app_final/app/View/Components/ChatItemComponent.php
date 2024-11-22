<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChatItemComponent extends Component
{
    public $message;
    public function __construct($message)
    {
        $this->message=$message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-item-component',['message'=>$this->message]);
    }
}
