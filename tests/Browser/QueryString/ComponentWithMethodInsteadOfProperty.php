<?php

namespace Tests\Browser\QueryString;

use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;

class ComponentWithMethodInsteadOfProperty extends BaseComponent
{
    public $foo = 'bar';

    public function queryString()
    {
        return ['foo'];
    }

    public function render()
    {
        return '<div>{{ $foo }}</div>';
    }
}
