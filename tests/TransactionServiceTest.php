<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\TransactionService;

class TransactionServiceTest extends PHPUnit\Framework\TestCase
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
        $orderId = 12345;
        $transactionData = [
            "transaction" => [
                "id" => 67890,
                "amount" => "10.00",
                "kind" => "authorization",
            ],
        ];
        $client = $this->mockClient([new Response(200, [], json_encode($transactionData))]);

        $transactionService = new TransactionService($client);
        $transaction = $transactionService->create($orderId, $transactionData["transaction"]);

        $this->assertIsObject($transaction);
        $this->assertEquals(67890, $transaction->id);
        $this->assertEquals("10.00", $transaction->amount);
        $this->assertEquals("authorization", $transaction->kind);
    }
}
