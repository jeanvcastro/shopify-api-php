<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\FulfillmentService;

class FulfillmentServiceTest extends PHPUnit\Framework\TestCase
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

    public function testFindAll()
    {
        $orderId = 12345;
        $fulfillmentsData = [
            "fulfillments" => [["id" => 1, "status" => "delivered"], ["id" => 2, "status" => "shipped"]],
        ];
        $client = $this->mockClient([new Response(200, [], json_encode($fulfillmentsData))]);

        $fulfillmentService = new FulfillmentService($client);
        $fulfillments = $fulfillmentService->findAll($orderId);

        $this->assertIsArray($fulfillments);
        $this->assertCount(2, $fulfillments);
        $this->assertEquals("delivered", $fulfillments[0]->status);
    }

    public function testCancel()
    {
        $fulfillmentId = 1;
        $fulfillmentData = ["fulfillment" => ["id" => $fulfillmentId, "status" => "cancelled"]];
        $client = $this->mockClient([new Response(200, [], json_encode($fulfillmentData))]);

        $fulfillmentService = new FulfillmentService($client);
        $fulfillment = $fulfillmentService->cancel($fulfillmentId);

        $this->assertIsObject($fulfillment);
        $this->assertEquals("cancelled", $fulfillment->status);
    }
}
