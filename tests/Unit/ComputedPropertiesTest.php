<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\Actcmscss;

class ComputedPropertiesTest extends TestCase
{
    /** @test */
    public function computed_property_is_accessable_within_blade_view()
    {
        Actcmscss::test(ComputedPropertyStub::class)
            ->assertSee('foo');
    }

    /** @test */
    public function injected_computed_property_is_accessable_within_blade_view()
    {
        Actcmscss::test(InjectedComputedPropertyStub::class)
            ->assertSee('bar');
    }

    /** @test */
    public function computed_property_is_memoized_after_its_accessed()
    {
        Actcmscss::test(MemoizedComputedPropertyStub::class)
            ->assertSee('int(2)');
    }

    /** @test */
    public function computed_property_cache_can_be_cleared()
    {
        Actcmscss::test(MemoizedComputedPropertyStub::class)
            ->assertSee('int(2)')
            ->call('callForgetComputed')
            ->assertSee('int(4)')
            ->call('callForgetComputed', 'foo')
            ->assertSee('int(6)')
            ->call('callForgetComputed', 'bar')
            ->assertSee('int(7)')
            ->call('callForgetComputed', ['foo', 'bar'])
            ->assertSee('int(9)')
            ->call('callForgetComputedWithTwoArgs', 'bar', 'foo')
            ->assertSee('int(11)');
    }
}

class ComputedPropertyStub extends Component
{
    public $upperCasedFoo = 'FOO_BAR';

    public function getFooBarProperty()
    {
        return strtolower($this->upperCasedFoo);
    }

    public function render()
    {
        return view('var-dump-foo-bar');
    }
}

class FooDependency {
    public $baz = 'bar';
}

class InjectedComputedPropertyStub extends Component
{
    public function getFooBarProperty(FooDependency $foo)
    {
        return $foo->baz;
    }

    public function render()
    {
        return view('var-dump-foo-bar');
    }
}

class MemoizedComputedPropertyStub extends Component
{
    public $count = 1;

    public function getFooProperty()
    {
        return $this->count += 1;
    }

    public function callForgetComputed($arg = null)
    {
        $this->foo;

        $this->forgetComputed($arg);

        $this->foo;
    }

    public function callForgetComputedWithTwoArgs($argOne, $argTwo)
    {
        $this->foo;

        $this->forgetComputed($argOne, $argTwo);

        $this->foo;
    }

    public function render()
    {
        // Access foo once here to start the cache.
        $this->foo;

        return view('var-dump-foo');
    }
}
