<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class DiscoverCommandTest extends TestCase
{
    /** @test */
    public function components_that_are_created_manually_are_automatically_added_to_the_manifest()
    {
        // Make the class & view directories, because otherwise, the manifest file cannot be created.
        File::makeDirectory($this->ActcmscssClassesPath());
        File::makeDirectory($this->ActcmscssViewsPath());

        // Ensure theres a manifest file that will become stale.
        Artisan::call('Actcmscss:discover');

        // Manually create the Actcmscss component.
        File::put(
            $this->ActcmscssClassesPath('ToBeDiscovered.php'),
<<<EOT
<?php

namespace App\Http\Actcmscss;

use Actcmscss\Component;

class ToBeDiscovered extends Component {
    public function render() { return view('Actcmscss.to-be-discovered'); }
}
EOT
        );

        File::put(
            $this->ActcmscssViewsPath('to-be-discovered.blade.php'),
<<<'EOT'
<div>I've been discovered!</div>
EOT
        );

        // We will not get an error because we will regenerate the manifest for the user automatically

        $output = view('render-component', [
            'component' => 'to-be-discovered',
        ])->render();

        $this->assertStringContainsString('I\'ve been discovered!', $output);
    }

    /** @test */
    public function the_manifest_file_is_automatically_created_if_none_exists()
    {
        $manifestPath = app()->bootstrapPath('cache/Actcmscss-components.php');

        // I'm calling "make:Actcmscss" as a shortcut to generate a manifest file
        Artisan::call('make:Actcmscss', ['name' => 'foo']);

        File::delete($manifestPath);

        // We need to refresh the appliction because otherwise, the manifest
        // will still be stored in the object memory.
        $this->refreshApplication();

        // Attempting to render a component should re-generate the manifest file.
        view('render-component', [
            'component' => 'foo',
        ])->render();

        $this->assertTrue(File::exists($manifestPath));
    }
}
