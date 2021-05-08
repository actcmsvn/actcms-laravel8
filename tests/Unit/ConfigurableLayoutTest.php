<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Illuminate\Support\Facades\Route;

class ConfigurableLayoutTest extends TestCase
{
    /** @test */
    public function uses_standard_app_layout_by_default()
    {
        Route::get('/configurable-layout', ComponentForConfigurableLayoutTest::class);

        $this
            ->get('/configurable-layout')
            ->assertSee('bar')
            ->assertDontSee('baz');
    }

    /** @test */
    public function can_configure_a_default_layout()
    {
        config()->set('Actcmscss.layout', 'layouts.app-with-baz-hardcoded');

        Route::get('/configurable-layout', ComponentForConfigurableLayoutTest::class);

        $this
            ->get('/configurable-layout')
            ->assertSee('bar')
            ->assertSee('baz');
    }
}

class ComponentForConfigurableLayoutTest extends Component
{
    public $name = 'bar';

    public function render()
    {
        return view('show-name');
    }
}
