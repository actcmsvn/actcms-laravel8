<?php

namespace Actcmscss\RenameMe;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Actcmscss\Actcmscss;

class SupportCollections
{
    static function init() { return new static; }

    function __construct()
    {
        Actcmscss::listen('property.dehydrate', function ($name, $value, $component, $response) {
            if (! $value instanceof Collection || $value instanceof EloquentCollection) return;


        });

        Actcmscss::listen('property.hydrate', function ($name, $value, $component, $request) {
            $collections = data_get($request->memo, 'dataMeta.collections', []);

            foreach ($collections as $name) {
                data_set($component, $name, collect(data_get($component, $name)));
            }
        });
    }
}
