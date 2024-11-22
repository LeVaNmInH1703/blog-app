<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommentComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public $name, $level;
    public $comment, $emojis;

    public function __construct($comment, $level = null)
    {
        $this->name = 'comment';
        $this->comment = $comment;
        $this->level = $level ?? 1;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comment-component', [
            'name' => $this->name,
            'comment' => $this->comment,
            'level' => $this->level
        ]);
    }
}
