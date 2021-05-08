<?php

namespace Tests\Browser\Nesting;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class NestedComponent extends BaseComponent
{
    public $output = '';

    public function render()
    {
        return View::file(__DIR__.'/view-nested.blade.php');
    }
}
