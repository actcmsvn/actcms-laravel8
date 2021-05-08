<?php

namespace Tests\Browser\SupportStringables;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                ->assertSee('Be excellent to each other');
        });
    }
}
