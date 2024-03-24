<?php
/** @noinspection PhpParamsInspection */

use GuzzleHttp\Psr7\Response;
use Jeanvcastro\ShopifyApiPhp\PaginatedResource;
use PHPUnit\Framework\TestCase;

class PaginatedResourceTest extends TestCase
{
    private function createResponse($body, $linkHeader = "")
    {
        return new Response(200, ["Content-Type" => "application/json", "Link" => $linkHeader], json_encode($body));
    }

    public function testCurrent()
    {
        $data = ["items" => ["item1", "item2"]];
        $response = $this->createResponse($data);
        $resource = new PaginatedResource($response, "items");

        $this->assertEquals(["item1", "item2"], $resource->current());
    }

    public function testPagination()
    {
        $linkHeader =
            '<https://example.com/api/resource?page_info=abc123>; rel="next", <https://example.com/api/resource?page_info=xyz789>; rel="previous"';
        $response = $this->createResponse(["items" => []], $linkHeader);
        $resource = new PaginatedResource($response, "items");

        $this->assertTrue($resource->hasNext());
        $this->assertEquals("abc123", $resource->getNextPageInfo());

        $this->assertTrue($resource->hasPrev());
        $this->assertEquals("xyz789", $resource->getPrevPageInfo());
    }
}
