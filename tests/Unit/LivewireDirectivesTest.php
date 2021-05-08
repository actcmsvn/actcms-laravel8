<?php

namespace Tests\Unit;

use Actcmscss\Actcmscss;
use Actcmscss\Component;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\ExpectationFailedException;

class ActcmscssDirectivesTest extends TestCase
{
    /** @test */
    public function component_is_loaded_with_blade_directive()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $output = view('render-component', [
            'component' => 'foo',
        ])->render();

        $this->assertStringContainsString('div', $output);
    }

    /** @test */
    public function can_assert_see_Actcmscss_on_standard_blade_view()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $fakeClass = new class {
            public function getContent()
            {
                return view('render-component', [
                    'component' => 'foo',
                ])->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertSeeActcmscss('foo');
    }

    /** @test */
    public function can_assert_see_Actcmscss_on_standard_blade_view_using_class_name()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $fakeClass = new class {
            public function getContent()
            {
                return view('render-component', [
                    'component' => 'foo',
                ])->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertSeeActcmscss(\App\Http\Actcmscss\Foo::class);
    }

    /** @test */
    public function assert_see_Actcmscss_fails_when_the_component_is_not_present()
    {
        $this->expectException(ExpectationFailedException::class);

        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $fakeClass = new class {
            public function getContent()
            {
                return view('null-view')->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertSeeActcmscss('foo');
    }

    /** @test */
    public function assert_see_Actcmscss_fails_when_the_component_is_not_present_using_class_name()
    {
        $this->expectException(ExpectationFailedException::class);

        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $fakeClass = new class {
            public function getContent()
            {
                return view('null-view')->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertSeeActcmscss(\App\Http\Actcmscss\Foo::class);
    }

    /** @test */
    public function can_assert_dont_see_Actcmscss_on_standard_blade_view()
    {
        $fakeClass = new class {
            public function getContent()
            {
                return view('null-view')->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertDontSeeActcmscss('foo');
    }

    /** @test */
    public function assert_dont_see_Actcmscss_fails_when_the_component_is_present()
    {
        $this->expectException(ExpectationFailedException::class);

        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $fakeClass = new class {
            public function getContent()
            {
                return view('render-component', [
                    'component' => 'foo',
                ])->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertDontSeeActcmscss('foo');
    }

    /** @test */
    public function assert_dont_see_Actcmscss_fails_when_the_component_is_present_using_class_name()
    {
        $this->expectException(ExpectationFailedException::class);

        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $fakeClass = new class {
            public function getContent()
            {
                return view('render-component', [
                    'component' => 'foo',
                ])->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertDontSeeActcmscss(\App\Http\Actcmscss\Foo::class);
    }

    /** @test */
    public function can_assert_dont_see_Actcmscss_on_standard_blade_view_using_class_name()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $fakeClass = new class {
            public function getContent()
            {
                return view('null-view')->render();
            }
        };

        $testResponse = new TestResponse($fakeClass);

        $testResponse->assertDontSeeActcmscss(\App\Http\Actcmscss\Foo::class);
    }

    /** @test */
    public function component_is_loaded_with_blade_directive_by_classname()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $output = view('render-component', [
            'component' => \App\Http\Actcmscss\Foo::class,
        ])->render();

        $this->assertStringContainsString('div', $output);
    }

    /** @test */
    public function this_directive_returns_javascript_component_object_string()
    {
        Actcmscss::test(ComponentForTestingDirectives::class)
            ->assertDontSee('@this')
            ->assertSee('window.Actcmscss.find(');
    }

    /** @test */
    public function this_directive_can_be_used_in_nested_blade_component()
    {
        Actcmscss::test(ComponentForTestingNestedThisDirective::class)
            ->assertDontSee('@this')
            ->assertSee('window.Actcmscss.find(');
    }
}

class ComponentForTestingDirectives extends Component
{
    public function render()
    {
        return view('this-directive');
    }
}

class ComponentForTestingNestedThisDirective extends Component
{
    public function render()
    {
        return view('nested-this-directive');
    }
}
