<?php

namespace Tests\Browser\Hooks;

use Actcmscss\Actcmscss;
use Laravel\Dusk\Browser;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function (Browser $browser) {
            Actcmscss::visit($browser, Component::class)
                ->tap(function ($b) {
                    $b->script([
                        "window.Actcmscss.hook('message.received', () => {
                            document.querySelector('[dusk=\"output\"]').value = 'before';
                        })",
                        "window.Actcmscss.hook('message.processed', () => {
                            document.querySelector('[dusk=\"output\"]').value += '_after';
                        })",
                    ]);
                })
                ->tap(function ($b) { $this->assertEquals('', $b->value('@output')); })
                ->waitForActcmscss()->click('@button')
                ->tap(function ($b) { $this->assertEquals('before_after', $b->value('@output')); })
            ;
        });
    }
}
