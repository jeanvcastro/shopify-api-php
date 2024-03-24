<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\ProductImageService;

class ProductImageServiceTest extends PHPUnit\Framework\TestCase
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
        $productId = 12345;
        $imageId = 67890;
        $imageData = ["image" => ["id" => $imageId, "src" => "http://example.com/image.jpg"]];
        $client = $this->mockClient([new Response(200, [], json_encode($imageData))]);

        $productImageService = new ProductImageService($client);
        $image = $productImageService->find($productId, $imageId);

        $this->assertIsObject($image);
        $this->assertEquals($imageId, $image->id);
        $this->assertEquals("http://example.com/image.jpg", $image->src);
    }
}
