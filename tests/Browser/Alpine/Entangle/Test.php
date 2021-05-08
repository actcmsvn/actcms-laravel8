<?php

namespace Tests\Browser\Alpine\Entangle;

use Actcmscss\Actcmscss;
use Tests\Browser\Alpine\Entangle\Component;
use Tests\Browser\Alpine\Entangle\EntangleConsecutiveActions;
use Tests\Browser\TestCase;

class Test extends TestCase
{
    public function test()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                /**
                 * Can mutate an array in Alpine and reflect in Actcmscss.
                 */
                ->assertDontSeeIn('@output.alpine', 'baz')
                ->assertDontSeeIn('@output.blade', 'baz')
                ->waitForActcmscss()->click('@button')
                ->assertSeeIn('@output.alpine', 'baz')
                ->assertSeeIn('@output.blade', 'baz')

                /**
                 * Can conditionally load in a new Alpine component that uses @entangle
                 */
                ->assertNotPresent('@bob.alpine')
                ->assertSeeIn('@bob.blade', 'before')
                ->waitForActcmscss()->click('@bob.show')
                ->assertSeeIn('@bob.alpine', 'before')
                ->assertSeeIn('@bob.blade', 'before')
                ->waitForActcmscss()->click('@bob.button')
                ->assertSeeIn('@bob.alpine', 'after')
                ->assertSeeIn('@bob.blade', 'after')
            ;
        });
    }

    public function test_watcher_is_fired_when_entangled_update_changes_other_entangled_data()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, ChangeMultipleDataAtTheSameTime::class)
                ->assertSeeIn('@output.alpine', 1)
                ->assertSeeIn('@output.alpine', 2)
                ->assertSeeIn('@output.alpine', 3)
                ->assertSeeIn('@output.alpine', 4)
                ->assertSeeIn('@output.Actcmscss', 1)
                ->assertSeeIn('@output.Actcmscss', 2)
                ->assertSeeIn('@output.Actcmscss', 3)
                ->assertSeeIn('@output.Actcmscss', 4)
                ->waitForActcmscss()->type('@search', 's')
                ->assertSeeIn('@output.alpine', 5)
                ->assertSeeIn('@output.alpine', 6)
                ->assertSeeIn('@output.alpine', 7)
                ->assertSeeIn('@output.alpine', 8)
                ->assertSeeIn('@output.Actcmscss', 5)
                ->assertSeeIn('@output.Actcmscss', 6)
                ->assertSeeIn('@output.Actcmscss', 7)
                ->assertSeeIn('@output.Actcmscss', 8)
            ;
        });
    }

    public function test_watcher_is_fired_each_time_entangled_data_changes()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, ToggleEntangled::class)
                ->assertSeeIn('@output.alpine', 'false')
                ->assertSeeIn('@output.Actcmscss', 'false')
                ->waitForActcmscss()->click('@toggle')
                ->assertSeeIn('@output.alpine', 'true')
                ->assertSeeIn('@output.Actcmscss', 'true')
                ->waitForActcmscss()->click('@toggle')
                ->assertSeeIn('@output.alpine', 'false')
                ->assertSeeIn('@output.Actcmscss', 'false')
            ;
        });
    }

    public function test_dot_defer()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, DeferDataUpdates::class)
                ->type('@input', 's')
                ->waitForActcmscss()->click('@submit')
                ->assertSeeIn('@output.alpine', 's')
                ->assertSeeIn('@output.Actcmscss', 's')
                ->append('@input', 's')
                ->waitForActcmscss()->click('@submit')
                ->assertSeeIn('@output.alpine', 'ss')
                ->assertSeeIn('@output.Actcmscss', 'ss')
            ;
        });
    }

    public function test_dot_defer_with_nested_data()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, DeferArrayDataUpdates::class)
                ->assertSeeIn('@output.alpine', 'guest')
                ->assertSeeIn('@output.Actcmscss', 'guest')
                ->select('@role-select', 'user')
                ->assertSeeIn('@output.alpine', 'user')
                ->assertSeeIn('@output.Actcmscss', 'guest')
                ->waitForActcmscss()->click('@submit')
                ->assertSeeIn('@output.alpine', 'guest')
                ->assertSeeIn('@output.Actcmscss', 'guest')
            ;
        });
    }

    public function test_entangle_does_not_throw_error_after_nested_array_removed()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, EntangleNestedArray::class)
                ->waitForActcmscss()->click('@add')
                ->waitForActcmscss()->click('@add')
                ->assertSeeIn('@output', "Item0")
                ->assertSeeIn('@output', "Item1")
                ->waitForActcmscss()->click('@remove')
                ->assertSeeIn('@output', "Item0")
                ->assertDontSeeIn('@output', "Item1")
            ;
        });
    }

    public function test_entangle_does_not_throw_wire_undefined_error_after_dynamically_adding_child_component()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, EntangleNestedParentComponent::class)
                ->assertSeeIn('@Actcmscss-output-test1', "test1")
                ->assertSeeIn('@alpine-output-test1', "test1")
                ->waitForActcmscss()->click('@add')
                ->assertSeeIn('@Actcmscss-output-test2', "test2")
                ->assertSeeIn('@alpine-output-test2', "test2")
            ;
        });
    }

    public function test_entangle_equality_check_ensures_alpine_does_not_update_Actcmscss()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, EntangleResponseCheck::class)
                ->assertSeeIn('@output', "false")
                ->waitForActcmscss()->click('@add')
                ->assertSeeIn('@output', "false")
            ;
        });
    }

    public function test_entangle_watchers_fire_on_consecutive_changes()
    {
        $this->browse(function ($browser) {
            Actcmscss::visit($browser, EntangleConsecutiveActions::class)

                // Trigger some consecutive alpine changes
                ->waitForActcmscss()->click('@alpineAdd')
                ->assertSeeIn('@alpineOutput', 0)
                ->assertDontSeeIn('@alpineOutput', 1)
                ->assertDontSeeIn('@alpineOutput', 2)
                ->assertSeeIn('@ActcmscssOutput', 0)
                ->assertDontSeeIn('@ActcmscssOutput', 1)
                ->assertDontSeeIn('@ActcmscssOutput', 2)

                ->waitForActcmscss()->click('@alpineAdd')
                ->assertSeeIn('@alpineOutput', 0)
                ->assertSeeIn('@alpineOutput', 1)
                ->assertDontSeeIn('@alpineOutput', 2)
                ->assertSeeIn('@ActcmscssOutput', 0)
                ->assertSeeIn('@ActcmscssOutput', 1)
                ->assertDontSeeIn('@ActcmscssOutput', 2)

                ->waitForActcmscss()->click('@alpineAdd')
                ->assertSeeIn('@alpineOutput', 0)
                ->assertSeeIn('@alpineOutput', 1)
                ->assertSeeIn('@alpineOutput', 2)
                ->assertSeeIn('@ActcmscssOutput', 0)
                ->assertSeeIn('@ActcmscssOutput', 1)
                ->assertSeeIn('@ActcmscssOutput', 2)


                // Trigger some consecutive Actcmscss changes
                ->waitForActcmscss()->click('@ActcmscssAdd')
                ->assertSeeIn('@alpineOutput', 3)
                ->assertDontSeeIn('@alpineOutput', 4)
                ->assertDontSeeIn('@alpineOutput', 5)
                ->assertSeeIn('@ActcmscssOutput', 3)
                ->assertDontSeeIn('@ActcmscssOutput', 4)
                ->assertDontSeeIn('@ActcmscssOutput', 5)

                ->waitForActcmscss()->click('@ActcmscssAdd')
                ->assertSeeIn('@alpineOutput', 3)
                ->assertSeeIn('@alpineOutput', 4)
                ->assertDontSeeIn('@alpineOutput', 5)
                ->assertSeeIn('@ActcmscssOutput', 3)
                ->assertSeeIn('@ActcmscssOutput', 4)
                ->assertDontSeeIn('@ActcmscssOutput', 5)

                ->waitForActcmscss()->click('@ActcmscssAdd')
                ->assertSeeIn('@alpineOutput', 3)
                ->assertSeeIn('@alpineOutput', 4)
                ->assertSeeIn('@alpineOutput', 5)
                ->assertSeeIn('@ActcmscssOutput', 0)
                ->assertSeeIn('@ActcmscssOutput', 4)
                ->assertSeeIn('@ActcmscssOutput', 5)
            ;
        });
    }
}
