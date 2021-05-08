<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Actcmscss\ActcmscssServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->makeACleanSlate();
        });

        $this->beforeApplicationDestroyed(function () {
            $this->makeACleanSlate();
        });

        parent::setUp();
    }

    public function makeACleanSlate()
    {
        Artisan::call('view:clear');

        File::deleteDirectory($this->ActcmscssViewsPath());
        File::deleteDirectory($this->ActcmscssClassesPath());
        File::deleteDirectory($this->ActcmscssTestsPath());
        File::delete(app()->bootstrapPath('cache/Actcmscss-components.php'));
    }

    protected function getPackageProviders($app)
    {
        return [
            ActcmscssServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [
            __DIR__.'/views',
            resource_path('views'),
        ]);

        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'Tests\HttpKernel');
    }

    protected function ActcmscssClassesPath($path = '')
    {
        return app_path('Http/Actcmscss'.($path ? '/'.$path : ''));
    }

    protected function ActcmscssViewsPath($path = '')
    {
        return resource_path('views').'/Actcmscss'.($path ? '/'.$path : '');
    }

    protected function ActcmscssTestsPath($path = '')
    {
        return base_path('tests/Feature/Actcmscss'.($path ? '/'.$path : ''));
    }
}
