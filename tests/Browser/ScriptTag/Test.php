<?php

namespace Tests\Browser\ScriptTag;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{

    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                ->assertScript('window.scriptTagWasCalled === undefined')
                ->waitForActcmscss()->click('@button')
                ->assertScript('window.scriptTagWasCalled === true')
            ;
        });
    }
}
