<?php

namespace Actcmscss\Commands;

class CpCommand extends CopyCommand
{
    protected $signature = 'actcmscss:cp {name} {new-name} {--inline} {--force}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
