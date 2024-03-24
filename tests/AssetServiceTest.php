<?php /** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\AssetService;
use Jeanvcastro\ShopifyApiPhp\Client;

class AssetServiceTest extends PHPUnit\Framework\TestCase
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
        $client = $this->mockClient([new Response(200, [], json_encode(["assets" => ["asset1", "asset2"]]))]);

        $assetService = new AssetService($client);
        $assets = $assetService->findAll(12345);

        $this->assertIsArray($assets);
        $this->assertCount(2, $assets);
    }

    public function testFind()
    {
        $client = $this->mockClient([new Response(200, [], json_encode(["asset" => ["key" => "value"]]))]);

        $assetService = new AssetService($client);
        $asset = $assetService->find(12345, "key");

        $this->assertIsObject($asset);
        $this->assertObjectHasProperty("key", $asset);
    }

    public function testCreateOrUpdateAsset()
    {
        $client = $this->mockClient([
            new Response(
                200,
                [],
                json_encode(["asset" => ["key" => "images/logo.png", "value" => "base64_image_content"]]),
            ),
        ]);

        $assetService = new AssetService($client);
        $asset = $assetService->createOrUpdateAsset(12345, "images/logo.png", "base64_image_content");

        $this->assertIsObject($asset);
        $this->assertEquals("images/logo.png", $asset->key);
        $this->assertEquals("base64_image_content", $asset->value);
    }

    public function testDelete()
    {
        $message = "images/logo.png was successfully deleted";
        $client = $this->mockClient([new Response(200, [], json_encode(["message" => $message]))]);

        $assetService = new AssetService($client);
        $response = $assetService->delete(12345, "images/logo.png");

        $this->assertIsObject($response);
        $this->assertEquals($message, $response->message);
    }
}
