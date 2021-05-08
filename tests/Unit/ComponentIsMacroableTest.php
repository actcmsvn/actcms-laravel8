<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\Actcmscss;

class ComponentIsMacroableTest extends TestCase
{
    /** @test */
    public function it_resolves_the_mount_parameters()
    {
        Component::macro('macroedMethod', function ($first, $second) {
            return [$first, $second];
        });

        Actcmscss::test(ComponentWithMacroedMethodStub::class)
            ->assertSet('foo', ['one', 'two']);
    }
}

class ComponentWithMacroedMethodStub extends Component
{
    public $foo;

    public function mount()
    {
        $this->foo = $this->macroedMethod('one', 'two');
    }

    public function render()
    {
        return view('null-view');
    }
}
