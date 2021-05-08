<?php

namespace Actcmscss\Commands;

class MvCommand extends MoveCommand
{
    protected $signature = 'actcmscss:mv {name} {new-name} {--inline} {--force}';

    protected function configure()
    {
        $this->setHidden(true);
    }
}
