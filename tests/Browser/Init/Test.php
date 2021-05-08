<?php

namespace Tests\Browser\Init;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * wire:init runs on page load.
                 */
                ->waitForText('foo')
                ->assertSee('foo')
            ;
        });
    }
}
