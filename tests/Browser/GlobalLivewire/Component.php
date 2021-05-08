<?php

namespace Tests\Browser\GlobalActcmscss;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class Component extends BaseComponent
{
    public $output = '';

    public function render()
    {
        return View::file(__DIR__.'/view.blade.php');
    }
}
