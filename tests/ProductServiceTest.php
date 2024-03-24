<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\Client;
use Jeanvcastro\ShopifyApiPhp\PaginatedResource;
use Jeanvcastro\ShopifyApiPhp\ProductService;

class ProductServiceTest extends PHPUnit\Framework\TestCase
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
        $productsData = [
            "products" => [["id" => 123, "title" => "Test Product 1"], ["id" => 456, "title" => "Test Product 2"]],
        ];
        $client = $this->mockClient([
            new Response(
                200,
                [
                    "Link" => "<https://example.com/products.json?page_info=abc&page=2>; rel=\"next\"",
                ],
                json_encode($productsData),
            ),
        ]);

        $productService = new ProductService($client);
        $page = $productService->findAll();
        $products = $page->current();

        $this->assertInstanceOf(PaginatedResource::class, $page);
        $this->assertTrue($page->hasNext());
        $this->assertFalse($page->hasPrev());
        $this->assertIsArray($products);
        $this->assertCount(2, $products);
        $this->assertEquals("Test Product 1", $products[0]->title);
        $this->assertEquals("Test Product 2", $products[1]->title);
    }

    public function testFind()
    {
        $productId = 123;
        $productData = ["product" => ["id" => $productId, "title" => "Test Product"]];
        $client = $this->mockClient([new Response(200, [], json_encode($productData))]);

        $productService = new ProductService($client);
        $product = $productService->find($productId);

        $this->assertIsObject($product);
        $this->assertEquals($productId, $product->id);
        $this->assertEquals("Test Product", $product->title);
    }
}
