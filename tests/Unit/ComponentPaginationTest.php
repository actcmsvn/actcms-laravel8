<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\Actcmscss;
use Actcmscss\WithPagination;

class ComponentPaginationTest extends TestCase
{
    /** @test */
    public function can_navigate_to_previous_page()
    {
        Actcmscss::test(ComponentWithPaginationStub::class)
            ->set('page', 2)
            ->call('previousPage')
            ->assertSet('page', 1);
    }

    /** @test */
    public function can_navigate_to_next_page()
    {
        Actcmscss::test(ComponentWithPaginationStub::class)
            ->call('nextPage')
            ->assertSet('page', 2);
    }

    /** @test */
    public function can_navigate_to_specific_page()
    {
        Actcmscss::test(ComponentWithPaginationStub::class)
            ->call('gotoPage', 5)
            ->assertSet('page', 5);
    }

    /** @test */
    public function previous_page_cannot_be_less_than_one()
    {
        Actcmscss::test(ComponentWithPaginationStub::class)
            ->call('previousPage')
            ->assertSet('page', 1);
    }
}

class ComponentWithPaginationStub extends Component
{
    use WithPagination;
    
    public function render()
    {
        return view('show-name', ['name' => 'example']);
    }
}
