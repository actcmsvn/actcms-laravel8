<?php

namespace Tests\Browser\Redirects;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * Flashing a message shows up right away, AND
                 * will show up if you redirect to a different
                 * page right after.
                 */
                ->assertNotPresent('@flash.message')
                ->waitForActcmscss()->click('@flash')
                ->assertPresent('@flash.message')
                ->waitForActcmscss()->click('@refresh')
                ->assertNotPresent('@flash.message')
                ->click('@redirect-with-flash')->waitForReload()
                ->assertPresent('@flash.message')
                ->waitForActcmscss()->click('@refresh')
                ->assertNotPresent('@flash.message')

                /**
                 * Actcmscss response is not handled if redirecting.
                 */
                ->refresh()
                ->assertSeeIn('@redirect.blade.output', 'foo')
                ->assertSeeIn('@redirect.alpine.output', 'foo')
                ->runScript('window.addEventListener("beforeunload", e => { e.preventDefault(); e.returnValue = ""; });')
                ->click('@redirect.button')
                ->pause(500)
                ->dismissDialog()
                ->assertSeeIn('@redirect.blade.output', 'foo')
                ->assertSeeIn('@redirect.alpine.output', 'foo')
            ;
        });
    }
}
