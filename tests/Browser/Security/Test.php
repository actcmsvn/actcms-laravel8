<?php

namespace Tests\Browser\Security;

use Laravel\Dusk\Browser;
use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test_that_persistent_middleware_is_applied_to_subsequent_Actcmscss_requests()
    {
        $this->browse(function (Browser $browser) {
            Actcmscss::visit($browser, Component::class)
                // See allow-listed middleware from original request.
                ->assertSeeIn('@middleware', '["Tests\\\\Browser\\\\AllowListedMiddleware","Tests\\\\Browser\\\\BlockListedMiddleware"]')
                ->assertDontSeeIn('@path', 'Actcmscss-dusk/Tests%5CBrowser%5CSecurity%5CComponent')

                ->waitForActcmscss()->click('@refresh')

                // See that the original request middleware was re-applied.
                ->assertSeeIn('@middleware', '["Tests\\\\Browser\\\\AllowListedMiddleware"]')
                ->assertSeeIn('@path', 'Actcmscss-dusk/Tests%5CBrowser%5CSecurity%5CComponent')

                ->waitForActcmscss()->click('@showNested')

                // Even to nested components shown AFTER the first load.
                ->assertSeeIn('@middleware', '["Tests\\\\Browser\\\\AllowListedMiddleware"]')
                ->assertSeeIn('@path', 'Actcmscss-dusk/Tests%5CBrowser%5CSecurity%5CComponent')
                ->assertSeeIn('@nested-middleware', '["Tests\\\\Browser\\\\AllowListedMiddleware"]')
                ->assertSeeIn('@path', 'Actcmscss-dusk/Tests%5CBrowser%5CSecurity%5CComponent')

                ->waitForActcmscss()->click('@refreshNested')

                // Make sure they are still applied when stand-alone requests are made to that component.
                ->assertSeeIn('@middleware', '["Tests\\\\Browser\\\\AllowListedMiddleware"]')
                ->assertSeeIn('@path', 'Actcmscss-dusk/Tests%5CBrowser%5CSecurity%5CComponent')
                ->assertSeeIn('@nested-middleware', '["Tests\\\\Browser\\\\AllowListedMiddleware"]')
                ->assertSeeIn('@path', 'Actcmscss-dusk/Tests%5CBrowser%5CSecurity%5CComponent')
            ;
        });
    }

    public function test_that_authentication_middleware_is_re_applied()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/force-login/1')
                ->visit('/with-authentication/Actcmscss-dusk/'.urlencode(Component::class))
                ->waitForActcmscssToLoad()
                // We're going to make a fetch request, but store the request payload
                // so we can replay it from a different page.
                ->tap(function ($b) {
                    $script = <<<'JS'
                        let unDecoratedFetch = window.fetch
                        let decoratedFetch = (...args) => {
                            window.localStorage.setItem(
                                'lastFetchArgs',
                                JSON.stringify(args),
                            )

                            return unDecoratedFetch(...args)
                        }
                        window.fetch = decoratedFetch
JS;

                    $b->script($script);
                })
                ->waitForActcmscss()->click('@refresh')
                // Now we logout.
                ->visit('/force-logout')
                // Now we try and re-run the request payload, expecting that
                // the "auth" middleware will be applied, recognize we've
                // logged out and throw an error in the response.
                ->tap(function ($b) {
                    $script = <<<'JS'
                        let args = JSON.parse(localStorage.getItem('lastFetchArgs'))

                        window.fetch(...args).then(i => i.text()).then(response => {
                            document.body.textContent = 'response-ready: '+JSON.stringify(response)
                        })
JS;

                    $b->script($script);
                })
                ->waitForText('response-ready: ')
                ->assertDontSee('Protected Content');
            ;
        });
    }

    public function test_that_authorization_middleware_is_re_applied()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/force-login/1')
                ->visit('/with-authorization/1/Actcmscss-dusk/'.urlencode(Component::class))
                ->waitForActcmscssToLoad()
                ->tap(function ($b) {
                    $script = <<<'JS'
                        let unDecoratedFetch = window.fetch
                        let decoratedFetch = (...args) => {
                            window.localStorage.setItem(
                                'lastFetchArgs',
                                JSON.stringify(args),
                            )

                            return unDecoratedFetch(...args)
                        }
                        window.fetch = decoratedFetch
JS;

                    $b->script($script);
                })
                ->waitForActcmscss()->click('@refresh')
                ->visit('/force-login/2')
                ->tap(function ($b) {
                    $script = <<<'JS'
                        let args = JSON.parse(localStorage.getItem('lastFetchArgs'))

                        window.fetch(...args).then(i => i.text()).then(response => {
                            document.body.textContent = 'response-ready: '+JSON.stringify(response)
                        })
JS;

                    $b->script($script);
                })
                ->waitForText('response-ready: ')
                ->assertDontSee('Protected Content');
            ;
        });
    }
}
