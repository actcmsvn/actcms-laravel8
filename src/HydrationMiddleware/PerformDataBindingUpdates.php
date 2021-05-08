<?php

namespace Actcmscss\HydrationMiddleware;

use Actcmscss\Actcmscss;
use Illuminate\Validation\ValidationException;

class PerformDataBindingUpdates implements HydrationMiddleware
{
    public static function hydrate($unHydratedInstance, $request)
    {
        try {
            foreach ($request->updates as $update) {
                if ($update['type'] !== 'syncInput') continue;

                $data = $update['payload'];

                $unHydratedInstance->syncInput($data['name'], $data['value']);
            }
        } catch (ValidationException $e) {
            Actcmscss::dispatch('failed-validation', $e->validator);

            $unHydratedInstance->setErrorBag($e->validator->errors());
        }
    }

    public static function dehydrate($instance, $response)
    {
        //
    }
}
