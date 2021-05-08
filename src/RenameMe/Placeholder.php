<?php

namespace Actcmscss\RenameMe;

use Actcmscss\Actcmscss;

class Placeholder
{
    static function init() { return new static; }

    function __construct()
    {
        Actcmscss::listen('component.hydrate', function ($component, $request) {
            //
        });

        Actcmscss::listen('component.dehydrate', function ($component, $response) {
            //
        });
    }
}
