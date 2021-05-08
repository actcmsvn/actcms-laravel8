<?php

namespace Tests\Browser\SupportDateTimes;

use Actcmscss\Actcmscss;
use Tests\Unit\TestCase;

class Test extends TestCase
{
    public function test()
    {
        Actcmscss::test(Component::class)
            ->assertSee('native-01/01/2001')
            ->assertSee('carbon-01/01/2001')
            ->assertSee('illuminate-01/01/2001')
            ->call('addDay')
            ->assertSee('native-01/02/2001')
            ->assertSee('carbon-01/02/2001')
            ->assertSee('illuminate-01/02/2001');
    }
}
