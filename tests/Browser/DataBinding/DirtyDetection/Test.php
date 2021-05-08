<?php

namespace Tests\Browser\DataBinding\DirtyDetection;

use Actcmscss\Actcmscss;
use Laravel\Dusk\Browser;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function (Browser $browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * If a value is changed server-side, the input updates.
                 */
                ->assertValue('@foo.input', 'initial')
                ->waitForActcmscss()->click('@foo.button')
                ->assertValue('@foo.input', 'changed')

                /**
                 * If an uninitialized nested value is reset server-side, the input updates.
                 */
                ->assertValue('@bar.input', '')
                ->type('@bar.input', 'changed')
                ->waitForActcmscss()->click('@bar.button')
                ->assertValue('@bar.input', '')
            ;
        });
    }
}
