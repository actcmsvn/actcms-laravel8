<?php

namespace Actcmscss\Exceptions;

class MissingFileUploadsTraitException extends \Exception
{
    use BypassViewHandler;

    public function __construct($component)
    {
        parent::__construct(
            "Cannot handle file upload without [Actcmscss\WithFileUploads] trait on the [{$component::getName()}] component class."
        );
    }
}
