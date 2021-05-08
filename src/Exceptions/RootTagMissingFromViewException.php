<?php

namespace Actcmscss\Exceptions;

class RootTagMissingFromViewException extends \Exception
{
    use BypassViewHandler;

    public function __construct()
    {
        parent::__construct(
            "Actcmscss encountered a missing root tag when trying to render a " .
            "component. \n When rendering a Blade view, make sure it contains a root HTML tag."
        );
    }
}
