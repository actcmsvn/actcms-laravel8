<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\ActcmscssManager;

class QueryParamsTest extends TestCase
{
    /** @test */
    public function it_sets_name_from_query_params()
    {
        $name = 'Actcmscss';

        app(ActcmscssManager::class)
            ->withQueryParams(['name' => $name])
            ->test(QueryParamsComponent::class)
            ->assertSet('name', $name);
    }

    /** @test */
    public function it_does_not_set_name_when_no_query_params_are_provided()
    {
        app(ActcmscssManager::class)
            ->test(QueryParamsComponent::class)
            ->assertSet('name', null);
    }
}

class QueryParamsComponent extends Component
{
    public $name;

    public function mount()
    {
        $this->name = app('request')->get('name');
    }

    public function render()
    {
        return app('view')->make('null-view');
    }
}
