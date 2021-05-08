<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class MoveCommandTest extends TestCase
{
    /** @test */
    public function component_is_renamed_by_move_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'bob']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Bob.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('bob.blade.php')));

        Artisan::call('Actcmscss:move', ['name' => 'bob', 'new-name' => 'lob']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Lob.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('lob.blade.php')));

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('Bob.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('bob.blade.php')));
    }

    /** @test */
    public function inline_component_is_renamed_by_move_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'bob', '--inline' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Bob.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('bob.blade.php')));

        Artisan::call('Actcmscss:move', ['name' => 'bob', 'new-name' => 'lob', '--inline' => true]);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Lob.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('lob.blade.php')));

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('Bob.php')));
    }

    /** @test */
    public function component_is_renamed_by_mv_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'bob']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Bob.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('bob.blade.php')));

        Artisan::call('Actcmscss:mv', ['name' => 'bob', 'new-name' => 'lob']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Lob.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('lob.blade.php')));

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('Bob.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('bob.blade.php')));
    }

    /** @test */
    public function nested_component_is_renamed_by_move_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'bob.lob']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Bob/Lob.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('bob/lob.blade.php')));

        Artisan::call('Actcmscss:move', ['name' => 'bob.lob', 'new-name' => 'bob.lob.law']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('Bob/Lob/Law.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('bob/lob/law.blade.php')));

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('Bob/Lob.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('bob/lob.blade.php')));
    }

    /** @test */
    public function multiword_component_is_renamed_by_move_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'bob-lob']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('BobLob.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('bob-lob.blade.php')));

        Artisan::call('Actcmscss:move', ['name' => 'bob-lob', 'new-name' => 'lob-law']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('/LobLaw.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('lob-law.blade.php')));

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('BobLob.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('bob-lob.blade.php')));
    }

    /** @test */
    public function pascal_case_component_is_automatically_converted_by_move_command()
    {
        Artisan::call('make:Actcmscss', ['name' => 'BobLob.BobLob']);

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('BobLob/BobLob.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('bob-lob/bob-lob.blade.php')));

        Artisan::call('Actcmscss:move', ['name' => 'BobLob.BobLob', 'new-name' => 'LobLaw.LobLaw']);

        $this->assertFalse(File::exists($this->ActcmscssClassesPath('BobLob/BobLob.php')));
        $this->assertFalse(File::exists($this->ActcmscssViewsPath('bob-lob/bob-lob.blade.php')));

        $this->assertTrue(File::exists($this->ActcmscssClassesPath('LobLaw/LobLaw.php')));
        $this->assertTrue(File::exists($this->ActcmscssViewsPath('lob-law/lob-law.blade.php')));
    }
}
