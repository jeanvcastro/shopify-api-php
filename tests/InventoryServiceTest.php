<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\InventoryService;

class InventoryServiceTest extends PHPUnit\Framework\TestCase
{
    private function mockClient(array $responses)
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(["handler" => $handlerStack]);

        $client = $this->createMock(Client::class);
        $client->method("getClient")->willReturn($guzzleClient);

        return $client;
    }

    public function testFind()
    {
        $inventoryItemId = 12345;
        $inventoryItemData = ["inventory_item" => ["id" => $inventoryItemId, "quantity" => 10]];
        $client = $this->mockClient([new Response(200, [], json_encode($inventoryItemData))]);

        $inventoryService = new InventoryService($client);
        $inventoryItem = $inventoryService->find($inventoryItemId);

        $this->assertIsObject($inventoryItem);
        $this->assertEquals($inventoryItemId, $inventoryItem->id);
        $this->assertEquals(10, $inventoryItem->quantity);
    }
}
