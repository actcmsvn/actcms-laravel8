<?php

namespace Actcmscss\Controllers;

class ActcmscssJavaScriptAssets
{
    use CanPretendToBeAFile;

    public function source()
    {
        return $this->pretendResponseIsFile(__DIR__.'/../../dist/actcmscss.js');
    }

    public function maps()
    {
        return $this->pretendResponseIsFile(__DIR__.'/../../dist/actcmscss.js.map');
    }
}
