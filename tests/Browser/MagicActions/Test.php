<?php

namespace Tests\Browser\MagicActions;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;
use Tests\Browser\MagicActions\Component;

class Test extends TestCase
{
    public function test_magic_toggle_can_toggle_properties()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                //Toggle boolean property
                ->assertSeeIn('@output', 'false')
                ->waitForActcmscss()->click('@toggle')
                ->assertSeeIn('@output', 'true')
                ->waitForActcmscss()->click('@toggle')
                ->assertSeeIn('@output', 'false')

                //Toggle nested boolean property
                ->assertSeeIn('@outputNested', 'false')
                ->waitForActcmscss()->click('@toggleNested')
                ->assertSeeIn('@outputNested', 'true')
                ->waitForActcmscss()->click('@toggleNested')
                ->assertSeeIn('@outputNested', 'false')
            ;
        });
    }
}
