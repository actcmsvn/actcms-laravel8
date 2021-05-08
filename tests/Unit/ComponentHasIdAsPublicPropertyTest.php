<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\Exceptions\CannotUseReservedActcmscssComponentProperties;
use Actcmscss\Actcmscss;

class ComponentHasIdAsPublicPropertyTest extends TestCase
{
    /** @test */
    public function public_id_property_is_set()
    {
        $component = Actcmscss::test(ComponentWithIdProperty::class);

        $this->assertNotNull($component->id());
    }

    /** @test */
    public function Actcmscsss_id_property_cannot_be_overridden_on_child_component()
    {
        $this->expectException(CannotUseReservedActcmscssComponentProperties::class);

        $component = Actcmscss::test(ComponentWithReservedProperties::class);

        $this->assertNotNull($component->id());
    }
}

class ComponentWithIdProperty extends Component
{
    public $name = 'Caleb';

    public function render()
    {
        return app('view')->make('show-name-with-this');
    }
}

class ComponentWithReservedProperties extends Component
{
    public $id = 'foo';

    public function render()
    {
        return app('view')->make('null-view');
    }
}
