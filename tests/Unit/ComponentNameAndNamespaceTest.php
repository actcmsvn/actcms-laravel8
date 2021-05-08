<?php

namespace Tests\Unit;

use Actcmscss\Actcmscss;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Actcmscss\Commands\ComponentParser;
use Actcmscss\ActcmscssComponentsFinder;

class ComponentNameAndNamespaceTest extends TestCase
{
    public function makeACleanSlate()
    {
        parent::makeACleanSlate();

        File::deleteDirectory(app_path('Custom'));
    }

    /** @test */
    public function can_get_name_with_Actcmscss_default_namespace()
    {
        File::makeDirectory($this->ActcmscssClassesPath('App'), 0755, true);
        File::makeDirectory($this->ActcmscssViewsPath('app'), 0755, true);

        File::put(
            $this->ActcmscssClassesPath('App/DefaultNamespace.php'),
<<<EOT
<?php

namespace App\Http\Actcmscss\App;

use Actcmscss\Component;

class DefaultNamespace extends Component {}
EOT
        );

        File::put(
            $this->ActcmscssViewsPath('app/default-namespace.blade.php'),
<<<EOT
<div>I've been namespaced!</div>
EOT
        );

        $component = Actcmscss::test('App\Http\Actcmscss\App\DefaultNamespace');

        $this->assertEquals('app.default-namespace', $component->instance()->getName());
    }

    /** @test */
    public function can_get_name_with_custom_namespace()
    {
        config(['Actcmscss.class_namespace' => 'Custom\\Controllers\\Http']);

        app()->instance(ActcmscssComponentsFinder::class, new ActcmscssComponentsFinder(
            new Filesystem,
            app()->bootstrapPath('cache/Actcmscss-components.php'),
            ComponentParser::generatePathFromNamespace(config('Actcmscss.class_namespace'))
        ));

        File::makeDirectory(app_path('Custom/Controllers/Http'), 0755, true);
        File::makeDirectory($this->ActcmscssViewsPath());

        File::put(
            app_path('Custom/Controllers/Http') . '/CustomNamespace.php',
<<<EOT
<?php

namespace Custom\Controllers\Http;

use Actcmscss\Component;

class CustomNamespace extends Component {}
EOT
        );

        File::put(
            $this->ActcmscssViewsPath('custom-namespace.blade.php'),
<<<EOT
<div>I've been namespaced!</div>
EOT
        );

        require(app_path('Custom/Controllers/Http') . '/CustomNamespace.php');
        $component = Actcmscss::test('Custom\Controllers\Http\CustomNamespace');

        $this->assertEquals('custom-namespace', $component->instance()->getName());
    }

    /** @test */
    public function can_get_name_with_app_namespace()
    {
        config(['Actcmscss.class_namespace' => 'App']);
        $finder = new ActcmscssComponentsFinder(
            new Filesystem,
            app()->bootstrapPath('cache/Actcmscss-components.php'),
            ComponentParser::generatePathFromNamespace(config('Actcmscss.class_namespace'))
        );

        app()->instance(ActcmscssComponentsFinder::class, $finder);

        File::makeDirectory($this->ActcmscssViewsPath());

        File::put(
            app_path() . '/AppNamespace.php',
<<<EOT
<?php

namespace App;

use Actcmscss\Component;

class AppNamespace extends Component {}
EOT
        );

        File::put(
            $this->ActcmscssViewsPath('app-namespace.blade.php'),
            <<<EOT
<div>I've been namespaced!</div>
EOT
        );

        require(app_path('') . '/AppNamespace.php');
        $component = Actcmscss::test('App\AppNamespace');

        $this->assertEquals('app-namespace', $component->instance()->getName());
        $this->assertContains('App\AppNamespace', $finder->getClassNames());
    }
}
