<?php

namespace Tests\Browser\DataBinding\InputSelect;

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
                 * Standard select.
                 */
                ->assertDontSeeIn('@single.output', 'bar')
                ->waitForActcmscss()->select('@single.input', 'bar')
                ->assertSelected('@single.input', 'bar')
                ->assertSeeIn('@single.output', 'bar')

                /**
                 * Standard select with value attributes.
                 */
                ->assertDontSeeIn('@single-value.output', 'par')
                ->waitForActcmscss()->select('@single-value.input', 'par')
                ->assertSelected('@single-value.input', 'par')
                ->assertSeeIn('@single-value.output', 'par')

                /**
                 * Standard select with value attributes.
                 */
                ->assertSeeIn('@single-number.output', '3')
                ->assertSelected('@single-number.input', '3')
                ->waitForActcmscss()->select('@single-number.input', '4')
                ->assertSeeIn('@single-number.output', '4')
                ->assertSelected('@single-number.input', '4')

                /**
                 * Select with placeholder default.
                 */
                ->assertSelected('@placeholder.input', '')
                ->assertDontSeeIn('@placeholder.output', 'foo')
                ->waitForActcmscss()->select('@placeholder.input', 'foo')
                ->assertSelected('@placeholder.input', 'foo')
                ->assertSeeIn('@placeholder.output', 'foo')

                /**
                 * Select multiple.
                 */
                ->assertDontSeeIn('@multiple.output', 'bar')
                ->waitForActcmscss()->select('@multiple.input', 'bar')
                ->assertSelected('@multiple.input', 'bar')
                ->assertSeeIn('@multiple.output', 'bar')
                ->waitForActcmscss()->select('@multiple.input', 'baz')
                ->assertSelected('@multiple.input', 'baz')
                ->assertSeeIn('@multiple.output', 'bar')
                ->assertSeeIn('@multiple.output', 'baz');
        });
    }
}
