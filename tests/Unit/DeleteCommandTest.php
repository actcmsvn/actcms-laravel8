<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class DeleteCommandTest extends TestCase
{
    /** @test */
    public function component_is_removed_by_delete_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $classPath = $this->ActcmscssClassesPath('Foo.php');
        $viewPath = $this->ActcmscssViewsPath('foo.blade.php');

        $this->assertTrue(File::exists($classPath));
        $this->assertTrue(File::exists($viewPath));

        Artisan::call('Actcmscss:delete', ['name' => 'foo', '--force' => true]);

        $this->assertFalse(File::exists($classPath));
        $this->assertFalse(File::exists($viewPath));
    }

    /** @test */
    public function inline_component_is_removed_by_delete_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo', '--inline' => true]);

        $classPath = $this->ActcmscssClassesPath('Foo.php');
        $viewPath = $this->ActcmscssViewsPath('foo.blade.php');

        $this->assertTrue(File::exists($classPath));
        $this->assertFalse(File::exists($viewPath));

        Artisan::call('Actcmscss:delete', ['name' => 'foo', '--force' => true, '--inline' => true]);

        $this->assertFalse(File::exists($classPath));
        $this->assertFalse(File::exists($viewPath));
    }

    /** @test */
    public function component_is_removed_by_rm_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $classPath = $this->ActcmscssClassesPath('Foo.php');
        $viewPath = $this->ActcmscssViewsPath('foo.blade.php');

        $this->assertTrue(File::exists($classPath));
        $this->assertTrue(File::exists($viewPath));

        Artisan::call('Actcmscss:rm', ['name' => 'foo', '--force' => true]);

        $this->assertFalse(File::exists($classPath));
        $this->assertFalse(File::exists($viewPath));
    }

    /** @test */
    public function component_is_removed_without_confirmation_if_forced()
    {
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        $classPath = $this->ActcmscssClassesPath('Foo.php');
        $viewPath = $this->ActcmscssViewsPath('foo.blade.php');

        $this->assertTrue(File::exists($classPath));
        $this->assertTrue(File::exists($viewPath));

        Artisan::call('Actcmscss:delete', ['name' => 'foo', '--force' => true]);

        $this->assertFalse(File::exists($classPath));
        $this->assertFalse(File::exists($viewPath));
    }
}
