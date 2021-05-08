<?php

namespace Actcmscss;

use Illuminate\View\View;
use Illuminate\Testing\TestResponse;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Actcmscss\Controllers\FileUploadHandler;
use Actcmscss\Controllers\FilePreviewHandler;
use Actcmscss\Controllers\HttpConnectionHandler;
use Actcmscss\Controllers\ActcmscssJavaScriptAssets;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Actcmscss\Commands\{
    CpCommand,
    MvCommand,
    RmCommand,
    CopyCommand,
    MakeCommand,
    MoveCommand,
    StubsCommand,
    TouchCommand,
    DeleteCommand,
    PublishCommand,
    ComponentParser,
    DiscoverCommand,
    S3CleanupCommand,
    MakeActcmscssCommand,
};
use Actcmscss\HydrationMiddleware\{
    RenderView,
    PerformActionCalls,
    CallHydrationHooks,
    PerformEventEmissions,
    HydratePublicProperties,
    PerformDataBindingUpdates,
    CallPropertyHydrationHooks,
    SecureHydrationWithChecksum,
    HashDataPropertiesForDirtyDetection,
    NormalizeServerMemoSansDataForJavaScript,
    NormalizeComponentPropertiesForJavaScript,
};
use Actcmscss\Macros\ViewMacros;

class ActcmscssServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConfig();
        $this->registerTestMacros();
        $this->registerActcmscssSingleton();
        $this->registerComponentAutoDiscovery();
    }

    public function boot()
    {
        $this->registerViews();
        $this->registerRoutes();
        $this->registerCommands();
        $this->registerRenameMes();
        $this->registerViewMacros();
        $this->registerTagCompiler();
        $this->registerPublishables();
        $this->registerBladeDirectives();
        $this->registerViewCompilerEngine();
        $this->registerHydrationMiddleware();

        // Bypass specific middlewares during Actcmscss requests.
        // These are usually helpful during a typical request, but
        // during Actcmscss requests, they can damage data properties.
        if (! $this->attemptToBypassRequestModifyingMiddlewareViaCallbacks()) {
            $this->bypassTheseMiddlewaresDuringActcmscssRequests([
                TrimStrings::class,
                ConvertEmptyStringsToNull::class,
                // If the app overrode "TrimStrings".
                \App\Http\Middleware\TrimStrings::class,
            ]);
        }
    }

    protected function registerActcmscssSingleton()
    {
        $this->app->singleton(ActcmscssManager::class);

        $this->app->alias(ActcmscssManager::class, 'actcmscss');
    }

    protected function registerComponentAutoDiscovery()
    {
        // Rather than forcing users to register each individual component,
        // we will auto-detect the component's class based on its kebab-cased
        // alias. For instance: 'examples.foo' => App\Http\Actcmscss\Examples\Foo

        // We will generate a manifest file so we don't have to do the lookup every time.
        $defaultManifestPath = $this->app['actcmscss']->isOnVapor()
            ? '/tmp/storage/bootstrap/cache/actcmscss-components.php'
            : app()->bootstrapPath('cache/actcmscss-components.php');

        $this->app->singleton(ActcmscssActcmscssComponentsFinder::class, function () use ($defaultManifestPath) {
            return new ActcmscssActcmscssComponentsFinder(
                new Filesystem,
                config('actcmscss.manifest_path') ?: $defaultManifestPath,
                ComponentParser::generatePathFromNamespace(
                    config('actcmscss.class_namespace')
                )
            );
        });
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/actcmscss.php', 'actcmscss');
    }

    protected function registerViews()
    {
        // This is mainly for overriding Laravel's pagination views
        // when a user applies the WithPagination trait to a component.
        $this->loadViewsFrom(
            __DIR__.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'pagination',
            'actcmscss'
        );
    }

    protected function registerRoutes()
    {
        RouteFacade::post('/actcmscss/message/{name}', HttpConnectionHandler::class)
            ->name('actcmscss.message')
            ->middleware(config('actcmscss.middleware_group', ''));

        RouteFacade::post('/actcmscss/upload-file', [FileUploadHandler::class, 'handle'])
            ->name('actcmscss.upload-file')
            ->middleware(config('actcmscss.middleware_group', ''));

        RouteFacade::get('/actcmscss/preview-file/{filename}', [FilePreviewHandler::class, 'handle'])
            ->name('actcmscss.preview-file')
            ->middleware(config('actcmscss.middleware_group', ''));

        RouteFacade::get('/actcmscss/actcmscss.js', [ActcmscssJavaScriptAssets::class, 'source']);
        RouteFacade::get('/actcmscss/actcmscss.js.map', [ActcmscssJavaScriptAssets::class, 'maps']);
    }

    protected function registerCommands()
    {
        if (! $this->app->runningInConsole()) return;

        $this->commands([
            MakeActcmscssCommand::class, // make:actcmscss
            MakeCommand::class,         // actcmscss:make
            TouchCommand::class,        // actcmscss:touch
            CopyCommand::class,         // actcmscss:copy
            CpCommand::class,           // actcmscss:cp
            DeleteCommand::class,       // actcmscss:delete
            RmCommand::class,           // actcmscss:rm
            MoveCommand::class,         // actcmscss:move
            MvCommand::class,           // actcmscss:mv
            StubsCommand::class,        // actcmscss:stubs
            DiscoverCommand::class,     // actcmscss:discover
            S3CleanupCommand::class,    // actcmscss:configure-s3-upload-cleanup
            PublishCommand::class,      // actcmscss:publish
        ]);
    }

    protected function registerTestMacros()
    {
        // Usage: $this->assertSeeActcmscss('counter');
        TestResponse::macro('assertSeeActcmscss', function ($component) {
            if (is_subclass_of($component, Component::class)) {
                $component = $component::getName();
            }

            $escapedComponentName = trim(htmlspecialchars(json_encode(['name' => $component])), '{}');

            \PHPUnit\Framework\Assert::assertStringContainsString(
                $escapedComponentName,
                $this->getContent(),
                'Cannot find Actcmscss component ['.$component.'] rendered on page.'
            );

            return $this;
        });

        // Usage: $this->assertDontSeeActcmscss('counter');
        TestResponse::macro('assertDontSeeActcmscss', function ($component) {
            if (is_subclass_of($component, Component::class)) {
                $component = $component::getName();
            }

            $escapedComponentName = trim(htmlspecialchars(json_encode(['name' => $component])), '{}');

            \PHPUnit\Framework\Assert::assertStringNotContainsString(
                $escapedComponentName,
                $this->getContent(),
                'Found Actcmscss component ['.$component.'] rendered on page.'
            );

            return $this;
        });
    }

    protected function registerViewMacros()
    {
        // Early versions of Laravel 7.x don't have this method.
        if (method_exists(ComponentAttributeBag::class, 'macro')) {
            ComponentAttributeBag::macro('wire', function ($name) {
                $entries = head($this->whereStartsWith('wire:'.$name));

                $directive = head(array_keys($entries));
                $value = head(array_values($entries));

                return new WireDirective($name, $directive, $value);
            });
        }

        View::mixin(new ViewMacros);
    }

    protected function registerTagCompiler()
    {
        if (method_exists($this->app['blade.compiler'], 'precompiler')) {
            $this->app['blade.compiler']->precompiler(function ($string) {
                return app(ActcmscssTagCompiler::class)->compile($string);
            });
        }
    }

    protected function registerPublishables()
    {
        $this->publishesToGroups([
            __DIR__.'/../dist' => public_path('vendor/actcmscss'),
        ], ['actcmscss', 'actcmscss:assets']);

        $this->publishesToGroups([
            __DIR__.'/../config/actcmscss.php' => base_path('config/actcmscss.php'),
        ], ['actcmscss', 'actcmscss:config']);

        $this->publishesToGroups([
            __DIR__.'/views/pagination' => $this->app->resourcePath('views/vendor/actcmscss'),
        ], ['actcmscss', 'actcmscss:pagination']);
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('this', [ActcmscssBladeDirectives::class, 'this']);
        Blade::directive('entangle', [ActcmscssBladeDirectives::class, 'entangle']);
        Blade::directive('actcmscss', [ActcmscssBladeDirectives::class, 'actcmscss']);
        Blade::directive('actcmscssStyles', [ActcmscssBladeDirectives::class, 'actcmscssStyles']);
        Blade::directive('actcmscssScripts', [ActcmscssBladeDirectives::class, 'actcmscssScripts']);
    }

    protected function registerViewCompilerEngine()
    {
        // This is a custom view engine that gets used when rendering
        // Actcmscss views. Things like letting certain exceptions bubble
        // to the handler, and registering custom directives like: "@this".
        $this->app->make('view.engine.resolver')->register('blade', function () {

            // If the application is using Ignition, make sure Actcmscss's view compiler
            // uses a version that extends Ignition's so it can continue to report errors
            // correctly. Don't change this class without first submitting a PR to Ignition.
            if (class_exists('Facade\Ignition\IgnitionServiceProvider')) {
                return new CompilerEngineForIgnition($this->app['blade.compiler']);
            }

            return new ActcmscssViewCompilerEngine($this->app['blade.compiler']);
        });
    }

    protected function registerRenameMes()
    {
        RenameMe\SupportEvents::init();
        RenameMe\SupportLocales::init();
        RenameMe\SupportChildren::init();
        RenameMe\SupportRedirects::init();
        RenameMe\SupportValidation::init();
        RenameMe\SupportFileUploads::init();
        RenameMe\OptimizeRenderedDom::init();
        RenameMe\SupportFileDownloads::init();
        RenameMe\SupportActionReturns::init();
        RenameMe\SupportBrowserHistory::init();
        RenameMe\SupportComponentTraits::init();
    }

    protected function registerHydrationMiddleware()
    {
        LifecycleManager::registerHydrationMiddleware([

            /* This is the core middleware stack of Actcmscss. It's important */
            /* to understand that the request goes through each class by the */
            /* order it is listed in this array, and is reversed on response */
            /*                                                               */
            /* ↓    Incoming Request                  Outgoing Response    ↑ */
            /* ↓                                                           ↑ */
            /* ↓    Secure Stuff                                           ↑ */
            /* ↓ */ SecureHydrationWithChecksum::class, /* --------------- ↑ */
            /* ↓ */ NormalizeServerMemoSansDataForJavaScript::class, /* -- ↑ */
            /* ↓ */ HashDataPropertiesForDirtyDetection::class, /* ------- ↑ */
            /* ↓                                                           ↑ */
            /* ↓    Hydrate Stuff                                          ↑ */
            /* ↓ */ HydratePublicProperties::class, /* ------------------- ↑ */
            /* ↓ */ CallPropertyHydrationHooks::class, /* ---------------- ↑ */
            /* ↓ */ CallHydrationHooks::class, /* ------------------------ ↑ */
            /* ↓                                                           ↑ */
            /* ↓    Update Stuff                                           ↑ */
            /* ↓ */ PerformDataBindingUpdates::class, /* ----------------- ↑ */
            /* ↓ */ PerformActionCalls::class, /* ------------------------ ↑ */
            /* ↓ */ PerformEventEmissions::class, /* --------------------- ↑ */
            /* ↓                                                           ↑ */
            /* ↓    Output Stuff                                           ↑ */
            /* ↓ */ RenderView::class, /* -------------------------------- ↑ */
            /* ↓ */ NormalizeComponentPropertiesForJavaScript::class, /* - ↑ */

        ]);

        LifecycleManager::registerInitialDehydrationMiddleware([

            /* Initial Response */
            /* ↑ */ [SecureHydrationWithChecksum::class, 'dehydrate'],
            /* ↑ */ [NormalizeServerMemoSansDataForJavaScript::class, 'dehydrate'],
            /* ↑ */ [HydratePublicProperties::class, 'dehydrate'],
            /* ↑ */ [CallPropertyHydrationHooks::class, 'dehydrate'],
            /* ↑ */ [CallHydrationHooks::class, 'initialDehydrate'],
            /* ↑ */ [RenderView::class, 'dehydrate'],
            /* ↑ */ [NormalizeComponentPropertiesForJavaScript::class, 'dehydrate'],

        ]);

        LifecycleManager::registerInitialHydrationMiddleware([

                [CallHydrationHooks::class, 'initialHydrate'],

        ]);
    }

    protected function attemptToBypassRequestModifyingMiddlewareViaCallbacks()
    {
        if (method_exists(TrimStrings::class, 'skipWhen') &&
            method_exists(ConvertEmptyStringsToNull::class, 'skipWhen')) {
            TrimStrings::skipWhen(function () {
                return Actcmscss::isProbablyActcmscssRequest();
            });

            ConvertEmptyStringsToNull::skipWhen(function () {
                return Actcmscss::isProbablyActcmscssRequest();
            });

            return true;
        }

        return false;
    }

    protected function bypassTheseMiddlewaresDuringActcmscssRequests(array $middlewareToExclude)
    {
        if (! Actcmscss::isProbablyActcmscssRequest()) return;

        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);

        $openKernel = new ObjectPrybar($kernel);

        $middleware = $openKernel->getProperty('middleware');

        $openKernel->setProperty('middleware', array_diff($middleware, $middlewareToExclude));
    }

    protected function publishesToGroups(array $paths, $groups = null)
    {
        if (is_null($groups)) {
            $this->publishes($paths);

            return;
        }

        foreach ((array) $groups as $group) {
            $this->publishes($paths, $group);
        }
    }
}
