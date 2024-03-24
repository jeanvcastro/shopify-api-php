<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\ThemeService;

class ThemeServiceTest extends PHPUnit\Framework\TestCase
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
        $themesData = [
            "themes" => [["id" => 123, "name" => "Test Theme 1"], ["id" => 456, "name" => "Test Theme 2"]],
        ];
        $client = $this->mockClient([new Response(200, [], json_encode($themesData))]);

        $themeService = new ThemeService($client);
        $themes = $themeService->findAll();

        $this->assertIsArray($themes);
        $this->assertCount(2, $themes);
        $this->assertEquals("Test Theme 1", $themes[0]->name);
        $this->assertEquals("Test Theme 2", $themes[1]->name);
    }

    public function testFind()
    {
        $themeId = 123;
        $themeData = ["theme" => ["id" => $themeId, "name" => "Test Theme"]];
        $client = $this->mockClient([new Response(200, [], json_encode($themeData))]);

        $themeService = new ThemeService($client);
        $theme = $themeService->find($themeId);

        $this->assertIsObject($theme);
        $this->assertEquals($themeId, $theme->id);
        $this->assertEquals("Test Theme", $theme->name);
    }
}
