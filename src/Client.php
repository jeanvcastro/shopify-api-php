<?php

namespace Jeanvcastro\ShopifyApiPhp;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private GuzzleClient $client;

    public function __construct(string $shopUrl, string $accessToken)
    {
        $this->client = new GuzzleClient([
            "base_uri" => "https://$shopUrl/admin/api/2024-01/",
            "headers" => [
                "Content-Type" => "application/json",
                "X-Shopify-Access-Token" => $accessToken,
            ],
        ]);
    }

    public function getClient(): GuzzleClient
    {
        return $this->client;
    }
}
