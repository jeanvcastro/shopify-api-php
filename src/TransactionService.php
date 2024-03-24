<?php

namespace Jeanvcastro\ShopifyApiPhp;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class TransactionService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception
     */
    public function create(int $orderId, array $transaction): stdClass
    {
        try {
            $response = $this->client->getClient()->post("orders/$orderId/transactions.json", [
                "json" => [
                    "transaction" => $transaction,
                ],
            ]);
            $content = json_decode($response->getBody()->getContents());
            return $content->transaction;
        } catch (GuzzleException $e) {
            throw new Exception("Error creating transaction: " . $e->getMessage());
        }
    }
}
