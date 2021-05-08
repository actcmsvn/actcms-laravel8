<?php

namespace Tests\Unit;

use Actcmscss\Component;
use Actcmscss\ActcmscssManager;
use Actcmscss\Exceptions\CorruptComponentPayloadException;

class RequestManipulatingMiddlewareAreDisabledForActcmscssRequestsTest extends TestCase
{
    /** @test */
    public function Actcmscss_request_data_doesnt_get_manipulated()
    {
        // This test is better done as a Laravel dusk test now.
        $this->markTestSkipped();

        // ActcmscssManager::$isActcmscssRequestTestingOverride = true;

        // $this->refreshApplication();

        // $component = app(ActcmscssManager::class)->test(ComponentWithStringPropertiesStub::class);

        // $this->withHeader('X-Actcmscss', 'true')->post("/Actcmscss/message/{$component->componentName}", [
        //     'updateQueue' => [],
        //     'name' => $component->componentName,
        //     'children' => $component->payload['children'],
        //     'data' => $component->payload['data'],
        //     'meta' => $component->payload['meta'],
        //     'id' => $component->payload['id'],
        //     'checksum' => $component->payload['checksum'],
        //     'locale' => $component->payload['locale'],
        //     'fromPrefetch' => [],
        // ])->assertJson(['data' => [
        //     'emptyString' => '',
        //     'oneSpace' => ' ',
        // ]]);

        // ActcmscssManager::$isActcmscssRequestTestingOverride = null;

        // $this->refreshApplication();
    }

    /** @test */
    public function non_Actcmscss_requests_do_get_manipulated()
    {
        // This test is better done as a Laravel dusk test now.
        $this->markTestSkipped();

        // $this->expectException(CorruptComponentPayloadException::class);

        // $component = app(ActcmscssManager::class)->test(ComponentWithStringPropertiesStub::class);

        // $this->withMiddleware()->post("/Actcmscss/message/{$component->componentName}", [
        //     'updateQueue' => [],
        //     'name' => $component->componentName,
        //     'children' => $component->payload['children'],
        //     'data' => $component->payload['data'],
        //     'meta' => $component->payload['meta'],
        //     'id' => $component->payload['id'],
        //     'checksum' => $component->payload['checksum'],
        //     'locale' => $component->payload['locale'],
        //     'fromPrefetch' => [],
        // ])->assertJson(['data' => [
        //     'emptyString' => null,
        //     'oneSpace' => null,
        // ]]);
    }
}

class ComponentWithStringPropertiesStub extends Component
{
    public $emptyString = '';
    public $oneSpace = ' ';

    public function render()
    {
        return app('view')->make('null-view');
    }
}
