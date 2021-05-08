<?php

namespace Tests\Browser\Init;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class Component extends BaseComponent
{
    public $output = '';

    public function setOutputToFoo()
    {
        $this->output = 'foo';
    }

    public function render()
    {
        return View::file(__DIR__.'/view.blade.php');
    }
}
