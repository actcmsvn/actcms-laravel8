<?php

namespace Actcmscss\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Actcmscss\ActcmscssComponentsFinder;
use function Actcmscss\str;

class FileManipulationCommand extends Command
{
    protected $parser;

    protected function ensureDirectoryExists($path)
    {
        if (! File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, $recursive = true, $force = true);
        }
    }

    public function refreshComponentAutodiscovery()
    {
        app(ActcmscssComponentsFinder::class)->build();
    }

    public function isFirstTimeMakingAComponent()
    {
        $namespace = str(config('actcmscss.class_namespace'))->replaceFirst(app()->getNamespace(), '');

        $actcmscssFolder = app_path($namespace->explode('\\')->implode(DIRECTORY_SEPARATOR));

        return ! File::isDirectory($actcmscssFolder);
    }

    public function writeWelcomeMessage()
    {
        $asciiLogo = <<<EOT
<fg=magenta>  _._</>
<fg=magenta>/ /<fg=white>o</>\ \ </> <fg=cyan> || ()                ()  __         </>
<fg=magenta>|_\ /_|</>  <fg=cyan> || || \\\// /_\ \\\ // || |~~ /_\   </>
<fg=magenta> <fg=cyan>|</>`<fg=cyan>|</>`<fg=cyan>|</> </>  <fg=cyan> || ||  \/  \\\_  \^/  || ||  \\\_   </>
EOT;
//     _._
        //   / /o\ \   || ()                ()  __
        //   |_\ /_|   || || \\\// /_\ \\\ // || |~~ /_\
//    |`|`|    || ||  \/  \\\_  \^/  || ||  \\\_
        $this->line("\n".$asciiLogo."\n");
        $this->line("\n<options=bold>Congratulations, you've created your first Actcmscss component!</> 🎉🎉🎉\n");
        if ($this->confirm('Would you like to show some love by starring the repo?')) {
            if(PHP_OS_FAMILY == 'Darwin') exec('open https://github.com/actcmscss/actcmscss');
            if(PHP_OS_FAMILY == 'Windows') exec('start https://github.com/actcmscss/actcmscss');
            if(PHP_OS_FAMILY == 'Linux') exec('xdg-open https://github.com/actcmscss/actcmscss');

            $this->line("Thanks! Means the world to me!");
        }
    }
}
