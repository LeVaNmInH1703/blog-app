<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserListComponent extends Component
{
    public $users,$title;
    public function __construct($users,$title="")
    {
        $this->users=$users;
        $this->title=$title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-list-component',[
            'users'=>$this->users,
            'title'=>$this->title,
        ]);
    }
}
