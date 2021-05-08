<?php

namespace Tests\Browser\PollingViewport;

use Laravel\Dusk\Browser;
use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function (Browser $browser) {
            Actcmscss::visit($browser, Component::class)
                ->assertSeeIn('@output', '1')
                ->waitForActcmscss(function () {})
                ->assertSeeIn('@output', '2')
                ->scrollTo('#bottom')
                ->pause(2000)
                ->scrollTo('#top')
                ->waitForActcmscss(function () {})
                ->assertSeeIn('@output', '3');
        });
    }
}
