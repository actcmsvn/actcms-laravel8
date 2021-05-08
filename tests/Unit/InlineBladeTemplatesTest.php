<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\Actcmscss;

class InlineBladeTemplatesTest extends TestCase
{
    /** @test */
    public function renders_inline_blade_template()
    {
        Actcmscss::test(ComponentWithInlineBladeTemplate::class)
            ->assertSee('foo');
    }
}

class ComponentWithInlineBladeTemplate extends Component
{
    public $name = 'foo';

    public function render()
    {
        return <<<'blade'
            <div>
                <span>{{ $name }}</span>
            </div>
blade;
    }
}
