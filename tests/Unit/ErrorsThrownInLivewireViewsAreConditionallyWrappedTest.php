<?php

namespace Tests\Unit;

use Exception;
use ErrorException;
use Actcmscss\Actcmscss;
use Actcmscss\Component;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\View;
use Actcmscss\Exceptions\BypassViewHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorsThrownInActcmscssViewsAreConditionallyWrappedTest extends TestCase
{
    /** @test */
    public function normal_errors_thrown_from_inside_a_Actcmscss_view_are_wrapped_by_the_blade_handler()
    {
        // Blade wraps thrown exceptions in "ErrorException" by default.
        $this->expectException(ErrorException::class);

        Actcmscss::component('foo', NormalExceptionIsThrownInViewStub::class);

        View::make('render-component', ['component' => 'foo'])->render();
    }

    /** @test */
    public function Actcmscss_errors_thrown_from_inside_a_Actcmscss_view_bypass_the_blade_wrapping()
    {
        // Exceptions that use the "BypassViewHandler" trait remain unwrapped.
        $this->expectException(SomeCustomActcmscssException::class);

        Actcmscss::component('foo', ActcmscssExceptionIsThrownInViewStub::class);

        View::make('render-component', ['component' => 'foo'])->render();
    }

    /** @test */
    public function errors_thrown_by_abort_404_function_are_not_wrapped()
    {
        $this->expectException(NotFoundHttpException::class);

        Actcmscss::component('foo', Abort404IsThrownInComponentMountStub::class);

        View::make('render-component', ['component' => 'foo'])->render();
    }

    /** @test */
    public function errors_thrown_by_abort_500_function_are_not_wrapped()
    {
        $this->expectException(HttpException::class);

        Actcmscss::component('foo', Abort500IsThrownInComponentMountStub::class);

        View::make('render-component', ['component' => 'foo'])->render();
    }

    /** @test */
    public function errors_thrown_by_authorization_exception_function_are_not_wrapped()
    {
        $this->expectException(AuthorizationException::class);

        Actcmscss::component('foo', AuthorizationExceptionIsThrownInComponentMountStub::class);

        View::make('render-component', ['component' => 'foo'])->render();
    }
}

class SomeCustomActcmscssException extends \Exception
{
    use BypassViewHandler;
}

class NormalExceptionIsThrownInViewStub extends Component
{
    public function render()
    {
        return app('view')->make('execute-callback', [
            'callback' => function () {
                throw new Exception;
            },
        ]);
    }
}

class ActcmscssExceptionIsThrownInViewStub extends Component
{
    public function render()
    {
        return app('view')->make('execute-callback', [
            'callback' => function () {
                throw new SomeCustomActcmscssException;
            },
        ]);
    }
}

class Abort404IsThrownInComponentMountStub extends Component
{
    public function mount()
    {
        abort(404);
    }

    public function render()
    {
        return app('view')->make('null-view');
    }
}

class Abort500IsThrownInComponentMountStub extends Component
{
    public function mount()
    {
        abort(500);
    }

    public function render()
    {
        return app('view')->make('null-view');
    }
}

class AuthorizationExceptionIsThrownInComponentMountStub extends Component
{
    public function mount()
    {
        throw new AuthorizationException;
    }

    public function render()
    {
        return app('view')->make('null-view');
    }
}
