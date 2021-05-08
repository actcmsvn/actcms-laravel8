<?php

namespace Actcmscss\RenameMe;

use Actcmscss\Actcmscss;

class OptimizeRenderedDom
{
    static function init() { return new static; }

    protected $htmlHashesByComponent = [];

    function __construct()
    {
        Actcmscss::listen('component.dehydrate.initial', function ($component, $response) {
            $response->memo['htmlHash'] = hash('crc32b', $response->effects['html']);
        });

        Actcmscss::listen('component.hydrate.subsequent', function ($component, $request) {
            $this->htmlHashesByComponent[$component->id] = $request->memo['htmlHash'];
        });

        Actcmscss::listen('component.dehydrate.subsequent', function ($component, $response) {
            $oldHash = $this->htmlHashesByComponent[$component->id] ?? null;

            $response->memo['htmlHash'] = $newHash = hash('crc32b', $response->effects['html']);

            if ($oldHash === $newHash) {
                $response->effects['html'] = null;
            }
        });
    }
}
