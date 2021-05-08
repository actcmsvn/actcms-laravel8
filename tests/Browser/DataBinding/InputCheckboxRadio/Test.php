<?php

namespace Tests\Browser\DataBinding\InputCheckboxRadio;

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
                 * Has initial value.
                 */
                ->assertChecked('@foo')
                ->assertSeeIn('@foo.output', 'true')

                /**
                 * Can set value
                 */
                ->waitForActcmscss()->uncheck('@foo')
                ->assertNotChecked('@foo')
                ->assertSeeIn('@foo.output', 'false')

                /**
                 * Can set value from an array
                 */
                ->assertNotChecked('@bar.a')->assertChecked('@bar.b')->assertNotChecked('@bar.c')
                ->assertSeeIn('@bar.output', '["b"]')
                ->waitForActcmscss()->check('@bar.c')
                ->assertNotChecked('@bar.a')->assertChecked('@bar.b')->assertChecked('@bar.c')
                ->assertSeeIn('@bar.output', '["b","c"]')

                /**
                 * Can set value from a number
                 */
                ->assertChecked('@baz')
                ;
        });
    }
}
