<?php

namespace Actcmscss\Commands;

class TouchCommand extends MakeCommand
{
    protected $signature = 'actcmscss:touch {name} {--force} {--inline} {--test} {--stub=default}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
