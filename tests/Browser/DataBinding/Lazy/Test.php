<?php

namespace Tests\Browser\DataBinding\Lazy;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test_it_only_sends_updates_for_fields_that_have_been_changed_upon_submit()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, LazyInputsWithUpdatesDisplayedComponent::class)
                ->type('@name', 'bob')
                ->waitForActcmscss()->click('@submit')
                ->assertSeeIn('@totalNumberUpdates', 2)
                ->assertSeeIn('@updatesList', 'syncInput - name')
                ->assertDontSeeIn('@updatesList', 'syncInput - description')
                ->assertSeeIn('@updatesList', 'callMethod - submit')

                ->type('@description', 'Test')
                ->waitForActcmscss()->click('@submit')
                ->assertSeeIn('@totalNumberUpdates', 2)
                ->assertDontSeeIn('@updatesList', 'syncInput - name')
                ->assertSeeIn('@updatesList', 'syncInput - description')
                ->assertSeeIn('@updatesList', 'callMethod - submit')
            ;
        });
    }

    public function test_it_sends_input_lazy_request_before_checkbox_request_in_the_same_request()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, LazyInputsWithUpdatesDisplayedComponent::class)
                ->type('@name', 'bob')
                ->waitForActcmscss()->check('@is_active')
                ->assertSeeIn('@totalNumberUpdates', 2)
                ->assertSeeIn('@updatesList', 'syncInput - name')
                ->assertSeeIn('@updatesList', 'syncInput - is_active')
            ;
        });
    }
}
