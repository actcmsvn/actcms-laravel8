<?php

namespace Actcmscss\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class StubsCommand extends Command
{
    protected $signature = 'actcmscss:stubs';

    protected $description = 'Publish Actcmscss stubs';

    protected $parser;

    public function handle()
    {
        if (! is_dir($stubsPath = base_path('stubs'))) {
            (new Filesystem)->makeDirectory($stubsPath);
        }

        file_put_contents(
            $stubsPath.'/actcmscss.stub',
            file_get_contents(__DIR__.'/actcmscss.stub')
        );

        file_put_contents(
            $stubsPath.'/actcmscss.inline.stub',
            file_get_contents(__DIR__.'/actcmscss.inline.stub')
        );

        file_put_contents(
            $stubsPath.'/actcmscss.view.stub',
            file_get_contents(__DIR__.'/actcmscss.view.stub')
        );

        file_put_contents(
            $stubsPath.'/actcmscss.test.stub',
            file_get_contents(__DIR__.'/actcmscss.test.stub')
        );

        $this->info('Stubs published successfully.');
    }
}
