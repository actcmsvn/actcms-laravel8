<?php

namespace Actcmscss;

use Actcmscss\ImplicitlyBoundMethod;
use Illuminate\Validation\ValidationException;

class LifecycleManager
{
    protected static $hydrationMiddleware = [];
    protected static $initialHydrationMiddleware = [];
    protected static $initialDehydrationMiddleware = [];

    public $request;
    public $instance;
    public $response;

    public static function fromSubsequentRequest($payload)
    {
        return tap(new static, function ($instance) use ($payload) {
            $instance->request = new Request($payload);
            $instance->instance = app('actcmscss')->getInstance($instance->request->name(), $instance->request->id());
        });
    }

    public static function fromInitialRequest($name, $id)
    {
        return tap(new static, function ($instance) use ($name, $id) {
            $instance->instance = app('actcmscss')->getInstance($name, $id);
            $instance->request = new Request([
                'fingerprint' => [
                    'id' => $id,
                    'name' => $name,
                    'locale' => app()->getLocale(),
                    'path' => Actcmscss::originalPath(),
                    'method' => Actcmscss::originalMethod(),
                ],
                'updates' => [],
                'serverMemo' => [],
            ]);
        });
    }

    public static function fromInitialInstance($component)
    {
        $name = app('actcmscss')->getAlias(get_class($component), $component->getName());

        return tap(new static, function ($instance) use ($component,  $name) {
            $instance->instance = $component;
            $instance->request = new Request([
                'fingerprint' => [
                    'id' => $component->id,
                    'name' => $name,
                    'locale' => app()->getLocale(),
                    'path' => Actcmscss::originalPath(),
                    'method' => Actcmscss::originalMethod(),
                ],
                'updates' => [],
                'serverMemo' => [],
            ]);
        });
    }

    public static function registerHydrationMiddleware(array $classes)
    {
        static::$hydrationMiddleware += $classes;
    }

    public static function registerInitialHydrationMiddleware(array $callables)
    {
        static::$initialHydrationMiddleware += $callables;
    }

    public static function registerInitialDehydrationMiddleware(array $callables)
    {
        static::$initialDehydrationMiddleware += $callables;
    }

    public function hydrate()
    {
        foreach (static::$hydrationMiddleware as $class) {
            $class::hydrate($this->instance, $this->request);
        }

        return $this;
    }

    public function initialHydrate()
    {
        foreach (static::$initialHydrationMiddleware as $callable) {
            $callable($this->instance, $this->request);
        }

        return $this;
    }

    public function mount($params = [])
    {
        // Assign all public component properties that have matching parameters.
        collect(array_intersect_key($params, $this->instance->getPublicPropertiesDefinedBySubClass()))
            ->each(function ($value, $property) {
                $this->instance->{$property} = $value;
            });

        if (method_exists($this->instance, 'mount')) {
            try {
                ImplicitlyBoundMethod::call(app(), [$this->instance, 'mount'], $params);
            } catch (ValidationException $e) {
                Actcmscss::dispatch('failed-validation', $e->validator);

                $this->instance->setErrorBag($e->validator->errors());
            }
        }

        Actcmscss::dispatch('component.mount', $this->instance, $params);

        return $this;
    }

    public function renderToView()
    {
        $this->instance->renderToView();

        return $this;
    }

    public function initialDehydrate()
    {
        $this->response = Response::fromRequest($this->request);

        foreach (array_reverse(static::$initialDehydrationMiddleware) as $callable) {
            $callable($this->instance, $this->response);
        }

        return $this;
    }

    public function dehydrate()
    {
        $this->response = Response::fromRequest($this->request);

        // The array is being reversed here, so the middleware dehydrate phase order of execution is
        // the inverse of hydrate. This makes the middlewares behave like layers in a shell.
        foreach (array_reverse(static::$hydrationMiddleware) as $class) {
            $class::dehydrate($this->instance, $this->response);
        }

        return $this;
    }

    public function toInitialResponse()
    {
        $this->response->embedThyselfInHtml();

        Actcmscss::dispatch('mounted', $this->response);

        return $this->response->toInitialResponse();
    }

    public function toSubsequentResponse()
    {
        return $this->response->toSubsequentResponse();
    }
}
