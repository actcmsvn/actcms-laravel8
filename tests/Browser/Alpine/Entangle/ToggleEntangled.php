<?php

namespace Tests\Browser\Alpine\Entangle;

use Actcmscss\Component as BaseComponent;

class ToggleEntangled extends BaseComponent
{
    public $active = false;


    public function render()
    {
        return
<<<'HTML'
<div>
    <div x-data="{
        active: @entangle('active')
    }">
        <div dusk="output.alpine" x-text="active"></div>
        <div dusk="output.Actcmscss">{{ $active ? 'true' : 'false' }}</div>
        <button dusk="toggle" x-on:click="active = !active">Toggle Active</button>
    </div>
</div>
HTML;
    }
}
