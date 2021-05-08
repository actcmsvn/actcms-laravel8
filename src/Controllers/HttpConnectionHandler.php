<?php

namespace Actcmscss\Controllers;

use Actcmscss\Actcmscss;
use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Request;
use Actcmscss\Connection\ConnectionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HttpConnectionHandler extends ConnectionHandler
{
    public function __invoke()
    {
        $this->applyPersistentMiddleware();

        return $this->handle(
            request([
                'fingerprint',
                'serverMemo',
                'updates',
            ])
        );
    }

    public function applyPersistentMiddleware()
    {
        try {
            $request = $this->makeRequestFromUrlAndMethod(
                Actcmscss::originalUrl(),
                Actcmscss::originalMethod()
            );
        } catch (NotFoundHttpException $e) {
            $request = $this->makeRequestFromUrlAndMethod(
                Str::replaceFirst(Actcmscss::originalUrl(), request('fingerprint')['locale'].'/', ''),
                Actcmscss::originalMethod()
            );
        }

        // Gather all the middleware for the original route, and filter it by
        // the ones we have designated for persistence on Actcmscss requests.
        $originalRouteMiddleware = app('router')->gatherRouteMiddleware($request->route());

        $persistentMiddleware = Actcmscss::getPersistentMiddleware();

        $filteredMiddleware = collect($originalRouteMiddleware)->filter(function ($middleware) use ($persistentMiddleware) {
            // Some middlewares can be closures.
            if (! is_string($middleware)) return false;

            return in_array(Str::before($middleware, ':'), $persistentMiddleware);
        })->toArray();

        // Now run the faux request through the original middleware with a custom pipeline.
        (new Pipeline(app()))
            ->send($request)
            ->through($filteredMiddleware)
            ->then(function() {
                // noop
            });
    }

    protected function makeRequestFromUrlAndMethod($url, $method = 'GET')
    {
        $request = Request::create($url, $method);

        if ($session = request()->getSession()) {
            $request->setLaravelSession($session);
        }

        $request->setUserResolver(request()->getUserResolver());

        $route = app('router')->getRoutes()->match($request);

        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        return $request;
    }
}
