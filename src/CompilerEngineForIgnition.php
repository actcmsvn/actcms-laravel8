<?php

namespace Actcmscss;

use Illuminate\View\Engines\PhpEngine;
use Facade\Ignition\Views\Engines\CompilerEngine;
use Actcmscss\ComponentConcerns\RendersActcmscssComponents;
use Throwable;

class CompilerEngineForIgnition extends CompilerEngine
{
    use RendersActcmscssComponents;

    protected function handleViewException(Throwable $e, $obLevel)
    {
        if ($this->shouldBypassExceptionForActcmscsse($e, $obLevel)) {
            // On Laravel 7 and before, there is no files property on the underlying
            // Illuminate\Views\Engines\CompilerEngine class, so pass null in this case
            (new PhpEngine($this->files ?? null))->handleViewException($e, $obLevel);

            return;
        }

        parent::handleViewException($e, $obLevel);
    }
}
