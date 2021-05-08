<?php

namespace Actcmscss\Exceptions;

class CannotBindToModelDataWithoutValidationRuleException extends \Exception
{
    use BypassViewHandler;

    public function __construct($key, $component)
    {
        parent::__construct(
            "Cannot bind property [$key] without a validation rule present in the [\$rules] array on Actcmscss component: [{$component}]."
        );
    }
}
