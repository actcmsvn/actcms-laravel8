<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Actcmscss\Component;
use Actcmscss\Actcmscss;
use function Actcmscss\str;

class ComponentSkipRenderTest extends TestCase
{
    /** @test */
    public function component_renders_like_normal()
    {
        $component = Actcmscss::test(ComponentSkipRenderStub::class);

        $this->assertTrue(
            str($component->payload['effects']['html'])->contains([$component->id(), 'foo'])
        );
    }

    /** @test */
    public function on_skip_render_render_is_not_called()
    {
        $component = Actcmscss::test(ComponentSkipRenderStub::class);

        $component->call('noop');

        $this->assertNull($component->payload['effects']['html']);
    }

    /** @test */
    public function on_redirect_in_mount_render_is_not_called()
    {
        $component = Actcmscss::test(ComponentSkipRenderOnRedirectInMountStub::class);

        $this->assertEquals('/foo', $component->payload['effects']['redirect']);
        $this->assertNull($component->lastRenderedView);

        Route::get('/403', ComponentSkipRenderOnRedirectInMountStub::class);
        $this->get('/403')->assertRedirect('/foo');

        $component = Actcmscss::test(ComponentSkipRenderOnRedirectHelperInMountStub::class);

        $this->assertStringEndsWith('/bar', $component->payload['effects']['redirect']);
        $this->assertNull($component->lastRenderedView);

        Route::get('/403', ComponentSkipRenderOnRedirectHelperInMountStub::class);
        $this->get('/403')->assertRedirect('/bar');
    }
}

class ComponentSkipRenderStub extends Component
{
    private $noop = false;

    public function noop()
    {
        $this->noop = true;

        $this->skipRender();
    }

    public function render()
    {
        if ($this->noop) {
            throw new \RuntimeException('Render should not be called after noop()');
        }

        return app('view')->make('null-view');
    }
}

class ComponentSkipRenderOnRedirectInMountStub extends Component
{
    public function mount()
    {
        $this->redirect('/foo');
    }

    public function render()
    {
        throw new \RuntimeException('Render should not be called on redirect');
    }
}

class ComponentSkipRenderOnRedirectHelperInMountStub extends Component
{
    public function mount()
    {
        return redirect('/bar');
    }

    public function render()
    {
        throw new \RuntimeException('Render should not be called on redirect');
    }
}
