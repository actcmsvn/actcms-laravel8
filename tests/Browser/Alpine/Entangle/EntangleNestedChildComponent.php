<?php

namespace Tests\Browser\Alpine\Entangle;

use Actcmscss\Component as BaseComponent;

class EntangleNestedChildComponent extends BaseComponent
{
    public $item;

    protected $rules = ['item.name' => ''];

    public function render()
    {
        return
<<<'HTML'
<div x-data="{ name: @entangle('item.name') }">
    <div dusk="Actcmscss-output-{{ $item['name']}}">{{ $item['name']}}</div>
    <div dusk="alpine-output-{{ $item['name']}}"><span x-text="name"></span></div>
</div>
HTML;
    }
}
