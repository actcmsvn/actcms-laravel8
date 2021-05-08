<?php

namespace Tests\Browser\Alpine\Transition;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // This test is too flaky for CI unfortunately.
        if (env('RUNNING_IN_CI')) $this->markTestSkipped();
    }

    public function test_dollar_sign_wire()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, DollarSignWireComponent::class);

            $this->runThroughTransitions($browser);

            $browser->waitForActcmscss()->click('@change-dom');

            $this->runThroughTransitions($browser);
        });
    }

    public function test_entangle()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, EntangleComponent::class);

            $this->runThroughTransitions($browser);

            $browser->waitForActcmscss()->click('@change-dom');

            $this->runThroughTransitions($browser);
        });
    }

    public function test_dot_defer()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, EntangleDeferComponent::class);

            // Because this is .defer, we want to mix Alpine and Actcmscss toggles.
            $this->runThroughTransitions($browser, 'button', 'button');
            $this->runThroughTransitions($browser, 'Actcmscss-button', 'Actcmscss-button');
            $this->runThroughTransitions($browser, 'button', 'Actcmscss-button');
            $browser->pause(500);
            $this->runThroughTransitions($browser, 'Actcmscss-button', 'button');

            $browser->waitForActcmscss()->click('@change-dom');

            $this->runThroughTransitions($browser, 'button', 'button');
            $this->runThroughTransitions($browser, 'Actcmscss-button', 'Actcmscss-button');
            $this->runThroughTransitions($browser, 'button', 'Actcmscss-button');
            $this->runThroughTransitions($browser, 'Actcmscss-button', 'button');
        });
    }

    protected function runThroughTransitions($browser, $firstHook = 'button', $secondHook = 'button')
    {
        return $browser
            // Transition out
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', '')
            ->click('@'.$firstHook)
            ->pause(100)
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.opacity', '0')
            ->pause(250)
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', 'none')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', 'none')

            // Transition back in
            ->click('@'.$secondHook)
            ->pause(100)
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.opacity', '1')
            ->pause(250)
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', '')

            // Transition out, but interrupt mid-way, then go back
            ->click('@'.$firstHook)
            ->pause(100)
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.opacity', '0')
            ->click('@'.$secondHook)
            ->pause(100)
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.opacity', '1')
            ->pause(250)
            ->assertScript('document.querySelector(\'[dusk="outer"]\').style.display', '')
            ->assertScript('document.querySelector(\'[dusk="inner"]\').style.display', '');
    }
}
