<?php /** @noinspection PhpParamsInspection */

use Jeanvcastro\ShopifyApiPhp\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ClientTest extends PHPUnit\Framework\TestCase
{
    private string $shopUrl = "your-shopify-store.myshopify.com";
    private string $accessToken = "your-access-token";

    public function testConstruct()
    {
        $client = new Client($this->shopUrl, $this->accessToken);
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testGetClientReturnsGuzzleInstance()
    {
        $client = new Client($this->shopUrl, $this->accessToken);
        $guzzleClient = $client->getClient();
        $this->assertInstanceOf(GuzzleClient::class, $guzzleClient);
    }

    public function testClientUsesCorrectBaseUri()
    {
        $client = new Client($this->shopUrl, $this->accessToken);
        $guzzleClient = $client->getClient();
        $config = $guzzleClient->getConfig();

        $this->assertEquals("https://{$this->shopUrl}/admin/api/2024-01/", $config["base_uri"]);
    }

    public function testClientHasCorrectHeaders()
    {
        $client = new Client($this->shopUrl, $this->accessToken);
        $guzzleClient = $client->getClient();
        $config = $guzzleClient->getConfig();

        $this->assertEquals("application/json", $config["headers"]["Content-Type"]);
        $this->assertEquals($this->accessToken, $config["headers"]["X-Shopify-Access-Token"]);
    }

    // Mock responses to test the behavior of the client with known responses
    public function testClientHandlesResponse()
    {
        $mock = new MockHandler([new Response(200, [], '{"status":"ok"}')]);

        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(["handler" => $handlerStack]);

        $client = new Client($this->shopUrl, $this->accessToken);
        $clientReflection = new ReflectionClass($client);
        $clientProperty = $clientReflection->getProperty("client");
        $clientProperty->setValue($client, $guzzleClient);

        $response = $client->getClient()->get("test");
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString('{"status":"ok"}', $response->getBody()->getContents());
    }
}
