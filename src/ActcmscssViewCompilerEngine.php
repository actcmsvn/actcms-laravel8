<?php

namespace Actcmscss;

use Illuminate\View\Engines\CompilerEngine;
use Actcmscss\ComponentConcerns\RendersActcmscssComponents;

class ActcmscssViewCompilerEngine extends CompilerEngine
{
    use RendersActcmscssComponents;
}
