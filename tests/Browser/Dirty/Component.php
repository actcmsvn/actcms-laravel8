<?php

namespace Tests\Browser\Dirty;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class Component extends BaseComponent
{
    public $foo = '';
    public $bar = '';
    public $baz = '';
    public $bob = '';

    public function render()
    {
        return View::file(__DIR__.'/view.blade.php');
    }
}
