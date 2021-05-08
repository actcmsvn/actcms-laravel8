<?php

namespace Actcmscss\Commands;

class RmCommand extends DeleteCommand
{
    protected $signature = 'actcmscss:rm {name} {--inline} {--force}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
