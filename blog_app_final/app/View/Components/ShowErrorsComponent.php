<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowErrorsComponent extends Component
{
    public $errors,$name;
    public function __construct($errors,$name)
    {
        $this->errors=$errors;
        $this->name=$name;
    }
    public function render(): View|Closure|string
    {
        return view('components.show-errors-component');
    }
}
