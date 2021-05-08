<?php

namespace Actcmscss\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'actcmscss:publish 
        { --assets : Indicates if Actcmscss\'s front-end assets should be published }
        { --config : Indicates if Actcmscss\'s config file should be published }
        { --pagination : Indicates if Actcmscss\'s pagination views should be published }';

    protected $description = 'Publish Actcmscss configuration';

    public function handle()
    {
        if ($this->option('assets')) {
            $this->publishAssets();
        } elseif ($this->option('config')) {
            $this->publishConfig();
        } elseif ($this->option('pagination')) {
            $this->publishPagination();
        } else {
            $this->publishAssets();
            $this->publishConfig();
            $this->publishPagination();
        }
    }

    public function publishAssets()
    {
        $this->call('vendor:publish', ['--tag' => 'actcmscss:assets', '--force' => true]);
    }

    public function publishConfig()
    {
        $this->call('vendor:publish', ['--tag' => 'actcmscss:config', '--force' => true]);
    }

    public function publishPagination()
    {
        $this->call('vendor:publish', ['--tag' => 'actcmscss:pagination', '--force' => true]);
    }
}
