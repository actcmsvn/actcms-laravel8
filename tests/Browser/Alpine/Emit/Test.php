<?php

namespace Tests\Browser\Alpine\Emit;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test_dollar_wire_emit_works()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, EmitComponent::class)
                ->assertDontSee('emit worked')
                ->waitForActcmscss()
                ->click('@emit')
                ->assertSee('emit worked')

                ->assertDontSee('emit self worked')
                ->waitForActcmscss()
                ->click('@emitSelf')
                ->assertSee('emit self worked')

                ->assertDontSee('emit up worked')
                ->waitForActcmscss()
                ->click('@emitUp')
                ->assertSee('emit up worked')

                ->assertDontSee('emit to worked')
                ->waitForActcmscss()
                ->click('@emitTo')
                ->assertSee('emit to worked')
            ;
        });
    }
}
