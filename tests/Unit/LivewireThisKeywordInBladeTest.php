<?php

namespace Tests\Unit;

use Actcmscss\Actcmscss;
use Actcmscss\Component;

class ActcmscssThisKeywordInBladeTest extends TestCase
{
    /** @test */
    public function this_keyword_will_reference_the_Actcmscss_component_class()
    {
        Actcmscss::test(ComponentForTestingThisKeyword::class)
            ->assertSee(ComponentForTestingThisKeyword::class);
    }
}

class ComponentForTestingThisKeyword extends Component
{
    public function render()
    {
        return view('this-keyword');
    }
}
