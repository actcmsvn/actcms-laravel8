<?php

namespace Actcmscss\Exceptions;

class CannotUseReservedActcmscssComponentProperties extends \Exception
{
    use BypassViewHandler;

    public function __construct($propertyName, $componentName)
    {
        parent::__construct(
            "Public property [{$propertyName}] on [{$componentName}] component is reserved for internal Actcmscss use."
        );
    }
}
