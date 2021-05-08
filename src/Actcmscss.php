<?php

namespace Actcmscss;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void component($alias, $viewClass)
 * @method static \Actcmscss\Testing\TestableActcmscss test($name, $params = [])
 * @method static \Actcmscss\ActcmscssManager actingAs($user, $driver = null)
 *
 * @see \Actcmscss\ActcmscssManager
 */
class Actcmscss extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'actcmscss';
    }
}
