<?php

namespace Actcmscss\RenameMe;

use Actcmscss\Actcmscss;

class SupportValidation
{
    static function init() { return new static; }

    function __construct()
    {
        Actcmscss::listen('component.dehydrate', function ($component, $response) {
            $errors = $component->getErrorBag()->toArray();

            // Only persist errors that were born from properties on the component
            // and not from custom validators (Validator::make) that were run.
            $response->memo['errors'] = collect($errors)
                ->filter(function ($value, $key) use ($component) {
                    return $component->hasProperty($key);
                })
                ->toArray();
        });

        Actcmscss::listen('component.hydrate', function ($component, $request) {
            $component->setErrorBag(
                $request->memo['errors'] ?? []
            );
        });
    }
}
