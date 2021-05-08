<?php

namespace Actcmscss\Exceptions;

class NonPublicComponentMethodCall extends \Exception
{
    use BypassViewHandler;

    public function __construct($method)
    {
        parent::__construct('Component method not found: ['.$method.']');
    }
}
