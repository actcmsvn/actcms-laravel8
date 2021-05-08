<?php

namespace Actcmscss\RenameMe;

use Actcmscss\Actcmscss;

class SupportChildren
{
    static function init() { return new static; }

    function __construct()
    {
        Actcmscss::listen('component.dehydrate', function ($component, $response) {
            $response->memo['children'] = $component->getRenderedChildren();
        });

        Actcmscss::listen('component.hydrate.subsequent', function ($component, $request) {
            $component->setPreviouslyRenderedChildren($request->memo['children']);
        });
    }
}
