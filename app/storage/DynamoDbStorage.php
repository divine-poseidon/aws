<?php


namespace app\storage;


use app\AwsServicesConfig;
use Aws\Credentials\Credentials;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Result;

class DynamoDbStorage implements StorageInterface
{
    private DynamoDbClient $client;

    public function __construct()
    {
        $this->client = new DynamoDbClient(AwsServicesConfig::getServicesParams());
    }

    public function add(array $data): void
    {
        $this->client->putItem($data);
    }

    public function get(array $params): Result
    {
        return $this->client->getItem($params);
    }
}