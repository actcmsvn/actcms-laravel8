<?php

namespace Tests\Browser\Extensions;

use Laravel\Dusk\Browser;
use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                ->tap(function (Browser $browser) {
                    $browser->script([
                        'window.renameMe = false',
                        "window.Actcmscss.directive('foo', (el, directive, component) => {
                            window.renameMe = true
                        })",
                    ]);
                })
                ->assertScript('window.renameMe', false)
                ->waitForActcmscss()->click('@refresh')
                ->assertScript('window.renameMe', true)
            ;
        });
    }
}
