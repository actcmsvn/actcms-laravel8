<?php

namespace Tests\Browser\DataBinding\InputText;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class Component extends BaseComponent
{
    public $foo = 'initial';
    public $bar = '';
    public $baz = '';
    public $bob = '';

    public function updateFooTo($value)
    {
        $this->foo = $value;
    }

    public function render()
    {
        return View::file(__DIR__.'/view.blade.php');
    }
}
