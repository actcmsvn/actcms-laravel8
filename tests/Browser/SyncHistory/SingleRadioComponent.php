<?php

namespace Tests\Browser\SyncHistory;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class SingleRadioComponent extends BaseComponent
{
    public $foo;

    protected $queryString = ['foo'];

    public function render()
    {
        return View::file(__DIR__.'/single-radio-component.blade.php');
    }
}
