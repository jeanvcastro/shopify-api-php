<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\OrderService;

class OrderServiceTest extends PHPUnit\Framework\TestCase
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

    public function testCreate()
    {
        $orderData = ["order" => ["line_items" => [["title" => "Test product", "quantity" => 1]]]];
        $client = $this->mockClient([new Response(200, [], json_encode($orderData))]);

        $orderService = new OrderService($client);
        $order = $orderService->create($orderData);

        $this->assertIsObject($order);
        $this->assertEquals("Test product", $order->line_items[0]->title);
    }

    public function testCancel()
    {
        $orderId = 12345;
        $client = $this->mockClient([
            new Response(200, [], json_encode(["order" => ["id" => $orderId, "status" => "cancelled"]])),
        ]);

        $orderService = new OrderService($client);
        $order = $orderService->cancel($orderId);

        $this->assertIsObject($order);
        $this->assertEquals("cancelled", $order->status);
    }

    public function testClose()
    {
        $orderId = 12345;
        $client = $this->mockClient([
            new Response(200, [], json_encode(["order" => ["id" => $orderId, "status" => "closed"]])),
        ]);

        $orderService = new OrderService($client);
        $order = $orderService->close($orderId);

        $this->assertIsObject($order);
        $this->assertEquals("closed", $order->status);
    }

    public function testFind()
    {
        $orderId = 12345;
        $client = $this->mockClient([new Response(200, [], json_encode(["order" => ["id" => $orderId]]))]);

        $orderService = new OrderService($client);
        $order = $orderService->find($orderId);

        $this->assertIsObject($order);
        $this->assertEquals($orderId, $order->id);
    }

    public function testUpdate()
    {
        $orderId = 12345;
        $orderData = ["order" => ["id" => $orderId, "email" => "test@example.com"]];
        $client = $this->mockClient([new Response(200, [], json_encode($orderData))]);

        $orderService = new OrderService($client);
        $order = $orderService->update($orderId, $orderData);

        $this->assertIsObject($order);
        $this->assertEquals("test@example.com", $order->email);
    }

    public function testDelete()
    {
        $orderId = 12345;
        $client = $this->mockClient([new Response(200, [], json_encode((object) []))]);

        $orderService = new OrderService($client);
        $result = $orderService->delete($orderId);

        $this->assertIsObject($result);
        $this->assertEquals($result, (object) []);
    }
}
