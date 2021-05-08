<?php

namespace Actcmscss\Exceptions;

class CorruptComponentPayloadException extends \Exception
{
    use BypassViewHandler;

    public function __construct($component)
    {
        parent::__construct(
            "Actcmscss encountered corrupt data when trying to hydrate the [{$component}] component. \n".
            "Ensure that the [name, id, data] of the Actcmscss component wasn't tampered with between requests."
        );
    }
}
