<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\WebhookService;

class WebhookServiceTest extends PHPUnit\Framework\TestCase
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
        $webhookData = ["webhook" => ["id" => 12345, "topic" => "orders/create"]];
        $client = $this->mockClient([new Response(200, [], json_encode($webhookData))]);

        $webhookService = new WebhookService($client);
        $webhook = $webhookService->create($webhookData["webhook"]);

        $this->assertIsObject($webhook);
        $this->assertEquals($webhookData["webhook"]["id"], $webhook->id);
        $this->assertEquals($webhookData["webhook"]["topic"], $webhook->topic);
    }

    public function testFindAll()
    {
        $webhooksData = [
            "webhooks" => [["id" => 12345, "topic" => "orders/create"], ["id" => 67890, "topic" => "orders/delete"]],
        ];
        $client = $this->mockClient([new Response(200, [], json_encode($webhooksData))]);

        $webhookService = new WebhookService($client);
        $webhooks = $webhookService->findAll();

        $this->assertIsArray($webhooks);
        $this->assertCount(2, $webhooks);
        $this->assertEquals($webhooksData["webhooks"][0]["topic"], $webhooks[0]->topic);
        $this->assertEquals($webhooksData["webhooks"][1]["topic"], $webhooks[1]->topic);
    }

    public function testFind()
    {
        $webhookId = 12345;
        $webhookData = ["webhook" => ["id" => $webhookId, "topic" => "orders/create"]];
        $client = $this->mockClient([new Response(200, [], json_encode($webhookData))]);

        $webhookService = new WebhookService($client);
        $webhook = $webhookService->find($webhookId);

        $this->assertIsObject($webhook);
        $this->assertEquals($webhookId, $webhook->id);
        $this->assertEquals("orders/create", $webhook->topic);
    }

    public function testDelete()
    {
        $webhookId = 12345;
        $client = $this->mockClient([new Response(200, [], json_encode((object) []))]);

        $webhookService = new WebhookService($client);
        $result = $webhookService->delete($webhookId);

        $this->assertIsObject($result);
        $this->assertEquals($result, (object) []);
    }
}
