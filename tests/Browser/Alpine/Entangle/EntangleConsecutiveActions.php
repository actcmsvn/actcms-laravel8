<?php

namespace Tests\Browser\Alpine\Entangle;

use Actcmscss\Component as BaseComponent;

class EntangleConsecutiveActions extends BaseComponent
{
    public $ActcmscssList = [];

    public function add()
    {
        $this->ActcmscssList[] = count($this->ActcmscssList);
    }

    public function render()
    {
        return
<<<'HTML'
<div x-data="{ alpineList: @entangle('ActcmscssList') }">
    <div>Alpine</div>
    <div dusk="alpineOutput">
        <template x-for="(item, key) in alpineList" :key="key">
            <div x-text="item"></div>
        </template>
    </div>

    <div>Actcmscss</div>
    <div dusk="ActcmscssOutput">
        @foreach($ActcmscssList as $key => $item)
            <div>{{ $item }}</div>
        @endforeach
    </div>

    <div>
        <button dusk="alpineAdd" type="button" x-on:click="alpineList.push(alpineList.length)">Add Alpine</button>
        <button dusk="ActcmscssAdd" type="button" wire:click="add">Add Actcmscss</button>
    </div>
</div>
HTML;
    }
}
