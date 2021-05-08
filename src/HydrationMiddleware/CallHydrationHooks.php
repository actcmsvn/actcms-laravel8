<?php

namespace Actcmscss\HydrationMiddleware;

use Actcmscss\Actcmscss;

class CallHydrationHooks implements HydrationMiddleware
{
    public static function hydrate($instance, $request)
    {
        Actcmscss::dispatch('component.hydrate', $instance, $request);
        Actcmscss::dispatch('component.hydrate.subsequent', $instance, $request);

        $instance->hydrate($request);
    }

    public static function dehydrate($instance, $response)
    {
        $instance->dehydrate($response);

        Actcmscss::dispatch('component.dehydrate', $instance, $response);
        Actcmscss::dispatch('component.dehydrate.subsequent', $instance, $response);
    }

    public static function initialDehydrate($instance, $response)
    {
        $instance->dehydrate($response);

        Actcmscss::dispatch('component.dehydrate', $instance, $response);
        Actcmscss::dispatch('component.dehydrate.initial', $instance, $response);
    }

    public static function initialHydrate($instance, $request)
    {
        Actcmscss::dispatch('component.hydrate', $instance, $request);
        Actcmscss::dispatch('component.hydrate.initial', $instance, $request);
    }
}
