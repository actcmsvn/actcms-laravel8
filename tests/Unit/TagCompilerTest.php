<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\Exceptions\ComponentAttributeMissingOnDynamicComponentException;
use Actcmscss\Actcmscss;
use Actcmscss\ActcmscssTagCompiler;

class TagCompilerTest extends TestCase
{
    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    public function setUp(): void
    {
        $this->compiler = new ActcmscssTagCompiler();
        parent::setUp();
    }

    /** @test */
    public function it_compiles_Actcmscss_self_closing_tags()
    {
        $alertComponent = '<Actcmscss:alert />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals("@Actcmscss('alert', [])", $result);
    }

    /** @test */
    public function it_compiles_Actcmscss_styles_tag()
    {
        $alertComponent = '<Actcmscss:styles />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals('@ActcmscssStyles', $result);
    }

    /** @test */
    public function it_compiles_Actcmscss_scripts_tag()
    {
        $alertComponent = '<Actcmscss:scripts />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals('@ActcmscssScripts', $result);
    }

    /** @test */
    public function it_compiles_Actcmscss_self_closing_tags_with_attributes()
    {
        $alertComponent = '<Actcmscss:alert type="danger" />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals("@Actcmscss('alert', ['type' => 'danger'])", $result);
    }

    /** @test */
    public function it_converts_kebab_attribute_names_to_camel_case()
    {
        $alertComponent = '<Actcmscss:alert alert-type="success" />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals("@Actcmscss('alert', ['alertType' => 'success'])", $result);
    }

    /** @test */
    public function it_compiles_Actcmscss_dynamic_component_self_closing_tags()
    {
        $alertComponent = '<Actcmscss:dynamic-component component="alert" type="warning" />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals("@Actcmscss('alert', ['type' => 'warning'])", $result);
    }

    /** @test */
    public function it_compiles_Actcmscss_dynamic_component_self_closing_tags_with_component_attribute_as_expression()
    {
        $alertComponent = '<Actcmscss:dynamic-component :component="\'alert\'" type="warning" />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals("@Actcmscss('alert', ['type' => 'warning'])", $result);
    }

    /** @test */
    public function it_throws_exception_if_Actcmscss_dynamic_component_is_missing_component_name_attribute()
    {
        $this->expectException(ComponentAttributeMissingOnDynamicComponentException::class);

        $alertComponent = '<Actcmscss:dynamic-component />';
        $this->compiler->compile($alertComponent);
    }

    /** @test */
    public function it_compiles_Actcmscss_dynamic_component_self_closing_tags_using_is_syntax()
    {
        $alertComponent = '<Actcmscss:is component="alert" type="warning" />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals("@Actcmscss('alert', ['type' => 'warning'])", $result);
    }

    /** @test */
    public function it_uses_existing_dynamic_component_if_one_exists()
    {
        Actcmscss::component('dynamic-component', DynamicComponent::class);

        $alertComponent = '<Actcmscss:dynamic-component />';
        $result = $this->compiler->compile($alertComponent);

        $this->assertEquals("@Actcmscss('dynamic-component', [])", $result);
    }
}

class DynamicComponent extends Component {

}
