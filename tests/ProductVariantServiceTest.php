<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\ProductVariantService;

class ProductVariantServiceTest extends PHPUnit\Framework\TestCase
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
        $variantId = 12345;
        $variantData = ["variant" => ["id" => $variantId, "title" => "Test Variant"]];
        $client = $this->mockClient([new Response(200, [], json_encode($variantData))]);

        $productVariantService = new ProductVariantService($client);
        $variant = $productVariantService->find($variantId);

        $this->assertIsObject($variant);
        $this->assertEquals($variantId, $variant->id);
        $this->assertEquals("Test Variant", $variant->title);
    }
}
