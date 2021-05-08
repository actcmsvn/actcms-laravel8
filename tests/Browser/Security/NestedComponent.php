<?php

namespace Tests\Browser\Security;

use Actcmscss\Component as BaseComponent;

class NestedComponent extends BaseComponent
{
    public $middleware = [];

    public function render()
    {
        $this->middleware = Component::$loggedMiddleware;

        return <<<'HTML'
<div>
    <span dusk="nested-middleware">@json($middleware)</span>
    <span dusk="nested-path">{{ \Actcmscss\Actcmscss::isDefinitelyActcmscssRequest() ? request('fingerprint')['path'] : '' }}</span>

    <button wire:click="$refresh" dusk="refreshNested">Refresh</button>
</div>
HTML;
    }
}
