<?php

namespace Tests\Browser\SupportCollections;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                ->assertSee('foo')
                ->assertDontSee('bar')
                ->waitForActcmscss()->click('@add-bar')
                ->assertSee('bar');
        });
    }
}
