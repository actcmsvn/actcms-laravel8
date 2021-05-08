<?php

namespace Tests\Browser\DataBinding\InputTextarea;

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
                 * Can change value
                 */
                ->assertDontSeeIn('@foo.output', 'changed')
                ->waitForActcmscss()->click('@foo.change')
                ->assertSeeIn('@foo.output', 'changed')

                /**
                 * Class change works as expected and doesn't wipe the textarea's value
                 */
                ->assertInputValue('@foo', 'changed')
                ->assertSourceMissing('class="foo"')
                ->waitForActcmscss()->click('@foo.add-class')
                ->assertInputValue('@foo', 'changed')
                ->assertSourceHas('class="foo"')

                /**
                 * Value will change if marked as dirty AND input is focused.
                 */
                ->waitForActcmscss(function ($b) {
                    $b->click('@foo');
                    $b->script('window.Actcmscss.first().set("foo", "changed-again")');
                })
                ->assertInputValue('@foo', 'changed-again')

                /**
                 * Value won't change if focused but NOT dirty.
                 */
                ->waitForActcmscss(function ($b) {
                    $b->click('@foo');
                    $b->script('window.Actcmscss.first().sync("foo", "changed-alot")');
                })
                ->assertSeeIn('@foo.output', 'changed-alot')
                ->assertInputValue('@foo', 'changed-again')

                /**
                 * Can set lazy value
                 */
                ->click('@baz') // Set focus.
                ->type('@baz', 'lazy')
                ->pause(150) // Wait for the amount of time it would have taken to do a round trip.
                ->assertDontSeeIn('@baz.output', 'lazy')
                ->waitForActcmscss()->click('@refresh') // Blur input and send action.
                ->assertSeeIn('@baz.output', 'lazy')

                /**
                 * Can set deferred value
                 */
                ->click('@bob') // Set focus.
                ->type('@bob', 'deferred')
                ->assertDontSeeIn('@bob.output', 'deferred')
                ->click('@foo') // Blur input to make sure this is more thans "lazy".
                ->pause(150) // Pause for upper-bound of most round-trip lengths.
                ->assertDontSeeIn('@bob.output', 'deferred')
                ->waitForActcmscss()->click('@refresh')
                ->assertSeeIn('@bob.output', 'deferred')
                ;
        });
    }
}
