<?php

namespace Tests\Browser\Actions;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * Basic action (click).
                 */
                ->waitForActcmscss()->click('@foo')
                ->assertSeeIn('@output', 'foo')

                /**
                 * Action with params.
                 */
                ->waitForActcmscss()->click('@bar')
                ->assertSeeIn('@output', 'barbell')

                /**
                 * Action with various parameter formatting differences.
                 */
                ->waitForActcmscss()->click('@ball')
                ->assertSeeIn('@output', 'abcdef')

                /**
                 * Action with no params, but still parenthesis.
                 */
                ->waitForActcmscss()->click('@bowl')
                ->assertSeeIn('@output', 'foo')

                /**
                 * Action with no params, but still parenthesis and having some spaces.
                 */
                ->waitForActcmscss()->click('@baw')
                ->assertSeeIn('@output', 'foo')

                /**
                 * wire:click.self
                 */
                ->waitForActcmscss()->click('@baz.inner')
                ->assertSeeIn('@output', 'foo')
                ->waitForActcmscss()->click('@baz.outer')
                ->assertSeeIn('@output', 'baz')

                /**
                 * Blur event and click event get sent together
                 */
                ->click('@bop.input') // Fucus.
                ->assertSeeIn('@output', 'baz')
                ->waitForActcmscss()->click('@bop.button')
                ->assertSeeIn('@output', 'bazbopbop')

                /**
                 * Two keydowns
                 */
                ->waitForActcmscss()->keys('@bob', '{enter}')
                ->assertSeeIn('@output', 'bazbopbopbobbob')

                /**
                 * If listening for "enter", other keys don't trigger the action.
                 */
                ->keys('@lob', 'k')
                ->pause(150)
                ->assertDontSeeIn('@output', 'lob')
                ->waitForActcmscss()->keys('@lob', '{enter}')
                ->assertSeeIn('@output', 'lob')

                /**
                 * keydown.shift.enter
                 */
                ->waitForActcmscss()->keys('@law', '{shift}', '{enter}')
                ->assertSeeIn('@output', 'law')

                /**
                 * keydown.space
                 */
                ->waitForActcmscss()->keys('@spa', '{space}')
                ->assertSeeIn('@output', 'spa')

                /**
                 * Elements are marked as read-only during form submission
                 */
                ->tap(function ($b) {
                    $this->assertNull($b->attribute('@blog.button', 'disabled'));
                    $this->assertNull($b->attribute('@blog.input', 'readonly'));
                    $this->assertNull($b->attribute('@blog.input.ignored', 'readonly'));
                })
                ->press('@blog.button')
                ->waitForActcmscss()->tap(function ($b) {
                    $this->assertEquals('true', $b->attribute('@blog.button', 'disabled'));
                    $this->assertEquals('true', $b->attribute('@blog.input', 'readonly'));
                    $this->assertNull($b->attribute('@blog.input.ignored', 'readonly'));
                })
                ->tap(function ($b) {
                    $this->assertNull($b->attribute('@blog.button', 'disabled'));
                    $this->assertNull($b->attribute('@blog.input', 'readonly'));
                })

                /**
                 * Elements are un-marked as readonly when form errors out.
                 */
                ->press('@boo.button')
                ->waitForActcmscss()->tap(function ($b) {
                    $this->assertEquals('true', $b->attribute('@boo.button', 'disabled'));
                })
                ->tap(function ($b) {
                    $this->assertNull($b->attribute('@blog.button', 'disabled'));
                })
                ->click('#Actcmscss-error')

                /**
                 * keydown.debounce
                 */
                ->keys('@bap', 'x')
                ->pause(50)
                ->waitForActcmscss()->assertDontSeeIn('@output', 'bap')
                ->assertSeeIn('@output', 'bap')
            ;
        });
    }
}
