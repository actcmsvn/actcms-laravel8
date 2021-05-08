<?php

namespace Actcmscss\Commands;

use Illuminate\Console\Command;
use Actcmscss\ActcmscssComponentsFinder;

class DiscoverCommand extends Command
{
    protected $signature = 'actcmscss:discover';

    protected $description = 'Regenerate Actcmscss component auto-discovery manifest';

    public function handle()
    {
        app(ActcmscssComponentsFinder::class)->build();

        $this->info('Actcmscss auto-discovery manifest rebuilt!');
    }
}
