<?php

namespace Actcmscss\RenameMe;

use Actcmscss\Actcmscss;

class SupportEvents
{
    static function init() { return new static; }

    function __construct()
    {
        Actcmscss::listen('component.hydrate', function ($component, $request) {
            //
        });

        Actcmscss::listen('component.dehydrate.initial', function ($component, $response) {
            $response->effects['listeners'] = $component->getEventsBeingListenedFor();
        });

        Actcmscss::listen('component.dehydrate', function ($component, $response) {
            $emits = $component->getEventQueue();
            $dispatches = $component->getDispatchQueue();

            if ($emits) {
                $response->effects['emits'] = $emits;
            }

            if ($dispatches) {
                $response->effects['dispatches'] = $dispatches;
            }
        });
    }
}
