<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\App;
use Actcmscss\Actcmscss;
use Actcmscss\Component;

class ComponentLocaleIsPersistedTest extends TestCase
{
    /** @test */
    public function a_Actcmscss_component_can_persist_its_locale()
    {
        // Set locale
        App::setLocale('en');
        $this->assertEquals(App::getLocale(), 'en');

        // Mount component and new ensure locale is set
        $component = Actcmscss::test(ComponentForLocalePersistanceHydrationMiddleware::class);
        $this->assertEquals(App::getLocale(), 'es');

        // Reset locale to ensure it isn't persisted in the test session
        App::setLocale('en');
        $this->assertEquals(App::getLocale(), 'en');

        // Verify locale is persisted from component mount
        $component->call('$refresh');
        $this->assertEquals(App::getLocale(), 'es');
    }
}

class ComponentForLocalePersistanceHydrationMiddleware extends Component
{
    public function mount()
    {
        App::setLocale('es');
    }

    public function render()
    {
        return view('null-view');
    }
}
