<?php

namespace Tests\Browser\Offline;

use Laravel\Dusk\Browser;
use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function (Browser $browser) {
            Actcmscss::visit($browser, Component::class)
                ->assertMissing('@whileOffline')
                ->assertScript('window.Actcmscss.components.ActcmscssIsOffline', false)
                ->offline()
                ->assertScript('window.Actcmscss.components.ActcmscssIsOffline', true)
                ->assertSeeIn('@whileOffline', 'Offline')
                ->online()
                ->assertMissing('@whileOffline')

                /**
                 * add element class while offline
                 */
                ->online()
                ->assertClassMissing('@addClass', 'foo')
                ->offline()
                ->assertHasClass('@addClass', 'foo')

                /**
                 * add element class while offline
                 */
                ->online()
                ->assertHasClass('@removeClass', 'hidden')
                ->offline()
                ->assertClassMissing('@removeClass', 'hidden')

                /**
                 * add element attribute while offline
                 */
                ->online()
                ->assertAttributeMissing('@withAttribute', 'disabled')
                ->offline()
                ->assertAttribute('@withAttribute', 'disabled', 'true')

                /**
                 * remove element attribute while offline
                 */
                ->online()
                ->assertAttribute('@withoutAttribute', 'disabled', 'true')
                ->offline()
                ->assertAttributeMissing('@withoutAttribute', 'disabled')
            ;
        });
    }
}
