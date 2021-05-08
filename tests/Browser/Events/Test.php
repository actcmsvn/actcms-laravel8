<?php

namespace Tests\Browser\Events;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * receive event from global fire
                 */
                ->waitForActcmscss()->tap(function ($browser) { $browser->script('window.Actcmscss.emit("foo", "bar")'); })
                ->waitUsing(5, 75, function () use ($browser) {
                    return $browser->assertSeeIn('@lastEventForParent', 'bar')
                             ->assertSeeIn('@lastEventForChildA', 'bar')
                             ->assertSeeIn('@lastEventForChildB', 'bar');
                })

                /**
                 * receive event from action fire
                 */
                ->waitForActcmscss()->click('@emit.baz')
                ->waitUsing(5, 75, function () use ($browser) {
                    return $browser->assertSeeIn('@lastEventForParent', 'baz')
                                   ->assertSeeIn('@lastEventForChildA', 'baz')
                                   ->assertSeeIn('@lastEventForChildB', 'baz');
                })

                /**
                 * receive event from component fire, and make sure global listener receives event too
                 */
                ->tap(function ($b) { $b->script([
                    "window.lastFooEventValue = ''",
                    "window.Actcmscss.on('foo', value => { lastFooEventValue = value })",
                ]);})
                ->waitForActcmscss()->click('@emit.bob')
                ->waitUsing(5, 75, function () use ($browser) {
                    return $browser->assertScript('window.lastFooEventValue', 'bob');
                })


                /**
                 * receive event from component fired only to ancestors, and make sure global listener doesnt receive it
                 */
                ->waitForActcmscss()->click('@emit.lob')
                ->waitUsing(5, 75, function () use ($browser) {
                    return $browser->assertSeeIn('@lastEventForParent', 'lob')
                                   ->assertSeeIn('@lastEventForChildA', 'bob')
                                   ->assertSeeIn('@lastEventForChildB', 'bob')
                                   ->assertScript('window.lastFooEventValue', 'bob');
                })

                /**
                 * receive event from action fired only to ancestors, and make sure global listener doesnt receive it
                 */
                ->waitForActcmscss()->click('@emit.law')
                ->waitUsing(5, 75, function () use ($browser) {
                    return $browser->assertSeeIn('@lastEventForParent', 'law')
                                   ->assertSeeIn('@lastEventForChildA', 'bob')
                                   ->assertSeeIn('@lastEventForChildB', 'bob')
                                   ->assertScript('window.lastFooEventValue', 'bob');
                })

                /**
                 * receive event from action fired only to component name, and make sure global listener doesnt receive it
                 */
                ->waitForActcmscss()->click('@emit.blog')
                ->waitUsing(5, 75, function () use ($browser) {
                    return $browser->assertSeeIn('@lastEventForParent', 'law')
                                   ->assertSeeIn('@lastEventForChildA', 'bob')
                                   ->assertSeeIn('@lastEventForChildB', 'blog')
                                   ->assertScript('window.lastFooEventValue', 'bob');
                })
            ;
        });
    }
}
