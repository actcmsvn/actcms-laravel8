<?php

namespace Tests\Browser\Morphdom;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * element root is DOM diffed
                 */
                ->assertAttributeMissing('@root', 'foo')
                ->waitForActcmscss()->click('@foo')
                ->assertAttribute('@root', 'foo', 'true')

                /**
                 * element inserted in the middle moves subsequent elements instead of removing them
                 */
                ->tap(function ($b) { $b->script([
                    "window.elementWasRemoved = false",
                    "Actcmscss.hook('element.removed', () => { window.elementWasRemoved = true })",
                ]);})
                ->waitForActcmscss()->click('@bar')
                ->assertScript('window.elementWasRemoved', false)

                /**
                 * element inserted before element with same tag name is handled as if they were different.
                 */
                ->tap(function ($b) { $b->script([
                    "window.lastAddedElement = false",
                    "Actcmscss.hook('element.initialized', el => { window.lastAddedElement = el })",
                ]);})
                ->waitForActcmscss()->click('@baz')
                ->assertScript('window.lastAddedElement.innerText', 'second')

                /**
                 * elements added with keys are recognized in the custom lookahead
                 */
                ->waitForActcmscss()->click('@bob')
                ->assertScript('Actcmscss.components.components()[0].morphChanges.added.length', 1)
                ->assertScript('Actcmscss.components.components()[0].morphChanges.removed.length', 0)


                ->tap(function ($b) { $b->script([
                    "window.lastAddedElement = false",
                    "window.lastUpdatedElement = false",
                    "Actcmscss.hook('element.updated', el => { window.lastUpdatedElement = el })",
                ]);})
                ->waitForActcmscss()->click('@qux')
                ->assertScript('window.lastAddedElement.innerText', 'second')
                ->assertScript('window.lastUpdatedElement.innerText', 'third')
            ;
        });
    }
}
