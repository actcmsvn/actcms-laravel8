<?php

namespace Tests\Unit;

use Actcmscss\Commands\ComponentParser;

class FileManipulationCommandParserTest extends TestCase
{
    /**
     * @test
     * @dataProvider classPathProvider
     */
    public function something($input, $component, $namespace, $classPath, $viewName, $viewPath)
    {
        $parser = new ComponentParser(
            'App\Http\Actcmscss',
            resource_path('views/Actcmscss'),
            $input
        );

        $this->assertEquals($component, $parser->component());
        $this->assertEquals($namespace, $parser->classNamespace());
        $this->assertEquals($this->normalizeDirectories(app_path($classPath)), $this->normalizeDirectories($parser->classPath()));
        $this->assertEquals($viewName, $parser->viewName());
        $this->assertEquals($this->normalizeDirectories(resource_path('views/'.$viewPath)), $this->normalizeDirectories($parser->viewPath()));
    }

    public function classPathProvider()
    {
        return [
            [
                'foo',
                'foo',
                'App\Http\Actcmscss',
                'Http/Actcmscss/Foo.php',
                'Actcmscss.foo',
                'Actcmscss/foo.blade.php',
            ],
            [
                'foo.bar',
                'bar',
                'App\Http\Actcmscss\Foo',
                'Http/Actcmscss/Foo/Bar.php',
                'Actcmscss.foo.bar',
                'Actcmscss/foo/bar.blade.php',
            ],
            [
                'foo.bar',
                'bar',
                'App\Http\Actcmscss\Foo',
                'Http/Actcmscss/Foo/Bar.php',
                'Actcmscss.foo.bar',
                'Actcmscss/foo/bar.blade.php',
            ],
            [
                'foo.bar',
                'bar',
                'App\Http\Actcmscss\Foo',
                'Http/Actcmscss/Foo/Bar.php',
                'Actcmscss.foo.bar',
                'Actcmscss/foo/bar.blade.php',
            ],
            [
                'foo-bar',
                'foo-bar',
                'App\Http\Actcmscss',
                'Http/Actcmscss/FooBar.php',
                'Actcmscss.foo-bar',
                'Actcmscss/foo-bar.blade.php',
            ],
            [
                'foo-bar.foo-bar',
                'foo-bar',
                'App\Http\Actcmscss\FooBar',
                'Http/Actcmscss/FooBar/FooBar.php',
                'Actcmscss.foo-bar.foo-bar',
                'Actcmscss/foo-bar/foo-bar.blade.php',
            ],
            [
                'FooBar',
                'foo-bar',
                'App\Http\Actcmscss',
                'Http/Actcmscss/FooBar.php',
                'Actcmscss.foo-bar',
                'Actcmscss/foo-bar.blade.php',
            ],
        ];
    }

    private function normalizeDirectories($subject)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $subject);
    }
}
