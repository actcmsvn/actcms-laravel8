<?php

namespace Tests\Unit;

use Actcmscss\Actcmscss;
use Actcmscss\Component;
use Illuminate\View\ComponentAttributeBag;

class BladeComponentAttributeMacrosTest extends TestCase
{
    public function test()
    {
        $bag = new ComponentAttributeBag([
            'wire:model.defer' => 'foo',
            'wire:click.debounce.100ms' => 'bar',
            'wire:keydown.enter' => 'baz',
        ]);

        $this->assertEquals('foo', $bag->wire('model'));
        $this->assertEquals(['defer'], $bag->wire('model')->modifiers()->toArray());
        $this->assertTrue($bag->wire('model')->hasModifier('defer'));
        $this->assertEquals('wire:model.defer="foo"', $bag->wire('model')->toHtml());

        $this->assertEquals('bar', $bag->wire('click'));
        $this->assertEquals(['debounce', '100ms'], $bag->wire('click')->modifiers()->toArray());

        $this->assertEquals('baz', $bag->wire('keydown'));
        $this->assertEquals(['enter'], $bag->wire('keydown')->modifiers()->toArray());
    }

    /** @test */
    public function entangle_directive_adds_dot_defer_if_defer_modifier_is_present()
    {
        $dom = Actcmscss::test(ComponentWithEntangleDirectiveUsedWithinBladeComponent::class)
            ->lastRenderedDom;

        $this->assertStringContainsString("{ foo: window.Actcmscss.find('", $dom);
        $this->assertStringContainsString("').entangle('foo').defer }", $dom);
        $this->assertStringContainsString("').entangle('bar') }", $dom);
    }
}

class ComponentWithEntangleDirectiveUsedWithinBladeComponent extends Component
{
    public function render()
    {
        return view('entangle');
    }
}
