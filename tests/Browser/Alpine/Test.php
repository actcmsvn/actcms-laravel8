<?php

namespace Tests\Browser\Alpine;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * ->dispatchBrowserEvent()
                 */
                ->assertDontSeeIn('@foo.output', 'bar')
                ->waitForActcmscss()->click('@foo.button')
                ->assertSeeIn('@foo.output', 'bar')

                /**
                 * Basic counter Alpine component.
                 */
                ->assertSeeIn('@bar.output', '0')
                ->click('@bar.button')
                ->assertSeeIn('@bar.output', '1')
                ->waitForActcmscss()->click('@bar.refresh')
                ->assertSeeIn('@bar.output', '1')

                /**
                 * get, set, and call to Actcmscss from Alpine.
                 */
                ->assertSeeIn('@baz.output', '0')
                ->assertSeeIn('@baz.get', '0')
                ->assertSeeIn('@baz.get.proxy', '0')
                ->assertSeeIn('@baz.get.proxy.magic', '0')
                ->waitForActcmscss()->click('@baz.set')
                ->assertSeeIn('@baz.output', '1')
                ->waitForActcmscss()->click('@baz.set.proxy')
                ->assertSeeIn('@baz.output', '2')
                ->waitForActcmscss()->click('@baz.set.proxy.magic')
                ->assertSeeIn('@baz.output', '3')
                ->waitForActcmscss()->click('@baz.call')
                ->assertSeeIn('@baz.output', '4')
                ->waitForActcmscss()->click('@baz.call.proxy')
                ->assertSeeIn('@baz.output', '5')
                ->waitForActcmscss()->click('@baz.call.proxy.magic')
                ->assertSeeIn('@baz.output', '6')

                /**
                 * get, set, and call with special characters
                 */
                ->assertSeeIn('@special.output', 'abc')
                ->assertSeeIn('@special.get', 'abc')
                ->assertSeeIn('@special.get.proxy', 'abc')
                ->assertSeeIn('@special.get.proxy.magic', 'abc')
                ->waitForActcmscss()->click('@special.set')
                ->assertSeeIn('@special.output', 'ž')
                ->waitForActcmscss()->click('@special.set.proxy')
                ->assertSeeIn('@special.output', 'žž')
                ->waitForActcmscss()->click('@special.set.proxy.magic')
                ->assertSeeIn('@special.output', 'žžž')
                ->waitForActcmscss()->click('@special.call')
                ->assertSeeIn('@special.output', 'žžžž')
                ->waitForActcmscss()->click('@special.call.proxy')
                ->assertSeeIn('@special.output', 'žžžžž')
                ->waitForActcmscss()->click('@special.call.proxy.magic')
                ->assertSeeIn('@special.output', 'žžžžžž')

                /**
                 * .call() return value
                 */
                ->assertDontSeeIn('@bob.output', '1')
                ->waitForActcmscss()->click('@bob.button.await')
                ->assertSeeIn('@bob.output', '1')
                ->waitForActcmscss()->click('@bob.button.promise')
                ->assertSeeIn('@bob.output', '2')

                /**
                 * $wire.entangle
                 */
                ->assertSeeIn('@lob.output', '6')
                ->waitForActcmscss(function ($b) {
                    $b->click('@lob.increment');
                })
                ->assertSeeIn('@lob.output', '7')
                ->waitForActcmscss()->click('@lob.decrement')
                ->assertSeeIn('@lob.output', '6')

                /**
                 * $wire.entangle nested property
                 */
                ->assertSeeIn('@law.output.alpine', '0')
                ->assertSeeIn('@law.output.wire', '0')
                ->assertSeeIn('@law.output.blade', '0')
                ->waitForActcmscss()->click('@law.increment.Actcmscss')
                ->assertSeeIn('@law.output.alpine', '1')
                ->assertSeeIn('@law.output.wire', '1')
                ->assertSeeIn('@law.output.blade', '1')
                ->waitForActcmscss()->click('@law.increment.alpine')
                ->assertSeeIn('@law.output.alpine', '2')
                ->assertSeeIn('@law.output.wire', '2')
                ->assertSeeIn('@law.output.blade', '2')

                /**
                 * Make sure property change from Actcmscss doesn't trigger an additional
                 * request because of @entangle.
                 */
                ->tap(function ($b) {
                    $b->script([
                        'window.ActcmscssRequestCount = 0',
                        "window.Actcmscss.hook('message.sent', () => { window.ActcmscssRequestCount++ })",
                    ]);
                })
                ->assertScript('window.ActcmscssRequestCount', 0)
                ->waitForActcmscss(function ($b) {
                    $b->click('@lob.reset');
                })
                ->assertScript('window.ActcmscssRequestCount', 1)
                ->pause(500)
                ->assertMissing('#Actcmscss-error')
                ->assertSeeIn('@lob.output', '100')

                /**
                 * $dispatch('input', value) works with wire:model
                 */
                ->assertSeeIn('@zorp.output', 'before')
                ->waitForActcmscss()->click('@zorp.button')
                ->assertSeeIn('@zorp.output', 'after')
            ;
        });
    }

    public function test_alpine_still_updates_even_when_Actcmscss_doesnt_update_html()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, SmallComponent::class)
                ->assertSeeIn('@output', '0')
                ->waitForActcmscss()->click('@button')
                ->assertSeeIn('@output', '1')
            ;
        });
    }

    public function test_alpine_registers_click_handlers_properly_on_Actcmscss_change()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, ClickComponent::class)
                ->waitForActcmscss()->click('@show')
                ->click('@click')
                ->assertSeeIn('@alpineClicksFired', 1)
                ->click('@click')
                ->assertSeeIn('@alpineClicksFired', 2)
                ->click('@click')
                ->assertSeeIn('@alpineClicksFired', 3)
                ->click('@componentClick')
                ->assertSeeIn('@alpineComponentClicksFired', 1)
                ->click('@componentClick')
                ->assertSeeIn('@alpineComponentClicksFired', 2)
                ->click('@componentClick')
                ->assertSeeIn('@alpineComponentClicksFired', 3)
            ;
        });
    }
}
