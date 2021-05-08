<?php

namespace Tests\Unit;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class MakeCommandTest extends TestCase
{
    /** @test */
    public function component_is_created_by_make_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Foo.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo.blade.php')));
    }

    /** @test */
    public function component_is_created_without_view_by_make_command_with_inline_option()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo', '--inline' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Foo.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('foo.blade.php')));
    }

    /** @test */
    public function component_test_is_created_by_make_command_with_test_option()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Foo.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('FooTest.php')));
    }

    /** @test */
    public function component_is_created_by_Actcmscss_make_command()
    {
        Artisan::call('Actcmscss:make', ['name' => 'foo', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Foo.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('FooTest.php')));
    }

    /** @test */
    public function component_is_created_by_touch_command()
    {
        Artisan::call('Actcmscss:touch', ['name' => 'foo', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Foo.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('FooTest.php')));
    }

    /** @test */
    public function dot_nested_component_is_created_by_make_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo.bar', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Foo/Bar.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo/bar.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('Foo/BarTest.php')));
    }

    /** @test */
    public function forward_slash_nested_component_is_created_by_make_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo/bar', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Foo/Bar.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo/bar.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('Foo/BarTest.php')));
    }

    /** @test */
    public function multiword_component_is_created_by_make_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo-bar', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('FooBar.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo-bar.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('FooBarTest.php')));
    }

    /** @test */
    public function pascal_case_component_is_automatically_converted_by_make_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'FooBar.FooBar', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('FooBar/FooBar.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo-bar/foo-bar.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('FooBar/FooBarTest.php')));
    }

    /** @test */
    public function pascal_case_component_with_double_backslashes_is_automatically_converted_by_make_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'FooBar\\FooBar', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('FooBar/FooBar.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('foo-bar/foo-bar.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('FooBar/FooBarTest.php')));
    }

    /** @test */
    public function snake_case_component_is_automatically_converted_by_make_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'text_replace', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('TextReplace.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('text-replace.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('TextReplaceTest.php')));
    }

    /** @test */
    public function snake_case_component_is_automatically_converted_by_make_command_on_nested_component()
    {
        Artisan::call('make:Actcmscss', ['name' => 'TextManager.text_replace', '--test' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('TextManager/TextReplace.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('text-manager/text-replace.blade.php')));
        $this->assertTrue(File::exists($this->ActcmscssTestsPath('TextManager/TextReplaceTest.php')));
    }

    /** @test */
    public function new_component_class_view_name_reference_matches_configured_view_path()
    {
        // We can't use Artisan::call here because we need to be able to set config vars.
        $this->app['config']->set('Actcmscss.view_path', resource_path('views/not-Actcmscss'));
        $this->app[Kernel::class]->call('make:Actcmscss', ['name' => 'foo']);

        $this->assertStringContainsString(
            "view('not-Actcmscss.foo')",
            File::get($this->ActcmscssClassesPath('Foo.php'))
        );
        $this->assertTrue(File::exists(resource_path('views/not-Actcmscss/foo.blade.php')));
    }

    /** @test */
    public function a_component_is_not_created_with_a_reserved_class_name()
    {
        Artisan::call('make:Actcmscss', ['name' => 'component']);

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('Component.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('component.blade.php')));

        Artisan::call('make:Actcmscss', ['name' => 'list']);

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('List.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('list.blade.php')));
    }
}
