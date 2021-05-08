<?php

namespace Actcmscss\RenameMe;

use Actcmscss\Actcmscss;
use Illuminate\Support\Facades\App;

class SupportLocales
{
    static function init() { return new static; }

    function __construct()
    {
        Actcmscss::listen('component.dehydrate.initial', function ($component, $response) {
            $response->fingerprint['locale'] = app()->getLocale();
        });

        Actcmscss::listen('component.hydrate.subsequent', function ($component, $request) {
           if ($locale = $request->fingerprint['locale']) {
                App::setLocale($locale);
            }
        });
    }
}
