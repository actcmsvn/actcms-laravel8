<?php

namespace Tests\Browser\Ignore;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * wire:ignore doesnt modify element or children after update
                 */
                ->assertAttributeMissing('@foo', 'some-new-attribute')
                ->waitForActcmscss()->click('@foo')
                ->assertAttributeMissing('@foo', 'some-new-attribute')

                /**
                 * wire:ignore ignores updates to children
                 */
                ->assertSeeIn('@bar.output', 'old')
                ->waitForActcmscss()->click('@bar')
                ->assertSeeIn('@bar.output', 'old')

                /**
                 * wire:ignore.self ignores updates to self, but not children
                 */
                ->assertSeeIn('@baz.output', 'old')
                ->assertAttributeMissing('@baz', 'some-new-attribute')
                ->waitForActcmscss()->click('@baz')
                ->assertAttributeMissing('@baz', 'some-new-attribute')
                ->assertSeeIn('@baz.output', 'new')

                /**
                 * adding .__Actcmscss_ignore to element ignores updates to children
                 */
                ->tap(function ($b) { $b->script("document.querySelector('[dusk=\"bob\"]').__Actcmscss_ignore = true"); })
                ->assertSeeIn('@bob.output', 'old')
                ->waitForActcmscss()->click('@bob')
                ->assertSeeIn('@bob.output', 'old')

                /**
                 * adding .__Actcmscss_ignore_self to element ignores updates to self, but not children
                 */
                ->tap(function ($b) { $b->script("document.querySelector('[dusk=\"lob\"]').__Actcmscss_ignore_self = true"); })
                ->assertSeeIn('@lob.output', 'old')
                ->assertAttributeMissing('@lob', 'some-new-attribute')
                ->waitForActcmscss()->click('@lob')
                ->assertAttributeMissing('@lob', 'some-new-attribute')
                ->assertSeeIn('@lob.output', 'new')
                ;
        });
    }
}
