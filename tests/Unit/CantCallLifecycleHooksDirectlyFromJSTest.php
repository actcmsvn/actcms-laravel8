<?php

namespace Tests\Unit;

use Actcmscss\Actcmscss;
use Actcmscss\Component;
use Actcmscss\Exceptions\DirectlyCallingLifecycleHooksNotAllowedException;

class CantCallLifecycleHooksDirectlyFromJSTest extends TestCase
{
    /** @test */
    public function cant_call_protected_lifecycle_hooks()
    {
        $this->assertTrue(
            collect([
                'mount',
                'hydrate',
                'hydrateFoo',
                'dehydrate',
                'dehydrateFoo',
                'updating',
                'updatingFoo',
                'updated',
                'updatedFoo',
            ])->every(function ($method) {
                return $this->cannotCallMethod($method);
            })
        );
    }

    protected function cannotCallMethod($method)
    {
        try {
            Actcmscss::test(ForProtectedLifecycleHooks::class)->call($method);
        } catch (DirectlyCallingLifecycleHooksNotAllowedException $e) {
            return true;
        }

        return false;
    }
}

class ForProtectedLifecycleHooks extends Component
{
    public function mount()
    {
        //
    }

    public function hydrate()
    {
        //
    }

    public function hydrateFoo()
    {
        //
    }

    public function dehydrate()
    {
        //
    }

    public function dehydrateFoo()
    {
        //
    }

    public function updating($name, $value)
    {
        //
    }

    public function updated($name, $value)
    {
        //
    }

    public function updatingFoo($value)
    {
        //
    }

    public function updatedFoo($value)
    {
        //
    }

    public function render()
    {
        return app('view')->make('null-view');
    }
}
