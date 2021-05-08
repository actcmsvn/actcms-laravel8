<?php

namespace Tests\Browser\Events;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class NestedComponentB extends BaseComponent
{
    protected $listeners = ['foo'];

    public $lastEvent = '';

    public function foo($value)
    {
        $this->lastEvent = $value;
    }

    public function render()
    {
        return View::file(__DIR__.'/nested-b.blade.php');
    }
}