<?php

namespace Tests\Browser\Alpine\Entangle;

use Actcmscss\Component as BaseComponent;

class ChangeMultipleDataAtTheSameTime extends BaseComponent
{
    public $ActcmscssList = [1,2,3,4];

    public $ActcmscssSearch;

    public function updatedActcmscssSearch()
    {
        $this->change();
    }

    public function change()
    {
        $this->ActcmscssList = [5,6,7,8];
    }

    public function render()
    {
        return
<<<'HTML'
<div>
    <div x-data="{
        alpineList: @entangle('ActcmscssList'),
        alpineSearch: @entangle('ActcmscssSearch')
    }">
        <div>
            <h1>Javascript show:</h1>

            <div dusk="output.alpine">
                <ul>
                    <template x-for="item in alpineList">
                        <li x-text="item"></li>
                    </template>
                </ul>
            </div>
        </div>

        <div>
            <h1>Server rendered show:</h1>

            <div dusk="output.Actcmscss">
                <ul>
                @foreach($ActcmscssList as $item)
                    <li>{{ $item }}</li>
                @endforeach
                </ul>
            </div>
        </div>

        <input dusk="search" x-model="alpineSearch" />
        <button dusk="change" wire:click="change">Change List</button>
    </div>
</div>
HTML;
    }
}
