<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\ShopService;

class ShopServiceTest extends PHPUnit\Framework\TestCase
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

    public function testShow()
    {
        $shopData = ["shop" => ["id" => 12345, "name" => "Test Shop"]];
        $client = $this->mockClient([new Response(200, [], json_encode($shopData))]);

        $shopService = new ShopService($client);
        $shop = $shopService->show();

        $this->assertIsObject($shop);
        $this->assertEquals("Test Shop", $shop->name);
    }
}
