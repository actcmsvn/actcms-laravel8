<?php

namespace Actcmscss\Connection;

use Actcmscss\LifecycleManager;

abstract class ConnectionHandler
{
    public function handle($payload)
    {
        return LifecycleManager::fromSubsequentRequest($payload)
            ->hydrate()
            ->renderToView()
            ->dehydrate()
            ->toSubsequentResponse();
    }
}
