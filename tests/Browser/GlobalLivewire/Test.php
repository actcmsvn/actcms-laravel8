<?php

namespace Tests\Browser\GlobalActcmscss;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * Event listeners are removed on teardown.
                 **/
                ->pause(250)
                ->tap(function ($b) { $b->script('window.Actcmscss.stop()'); })
                ->click('@foo')
                ->pause(100)
                ->assertDontSeeIn('@output', 'foo')
                ->refresh()

                /**
                 * Rescanned components dont register twice.
                 **/
                ->tap(function ($b) { $b->script("Actcmscss.rescan()"); })
                ->waitForActcmscss()->click('@foo')
                ->assertSeeIn('@output', 'foo')
                ->refresh()

                /**
                 * window.Actcmscss.onLoad callback is called when Actcmscss is initialized
                 */
                ->assertScript('window.isLoaded', true)

                /**
                 * Actcmscss:load DOM event is fired after start
                 */
                ->assertScript('window.loadEventWasFired', true)
            ;
        });
    }
}
