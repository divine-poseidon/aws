<?php


namespace app\storage;


use Aws\Credentials\Credentials;
use Aws\DynamoDb\DynamoDbClient;

class DynamoDbStorage implements StorageInterface
{
    private DynamoDbClient $client;

    public function __construct()
    {
        $this->client = new DynamoDbClient(
            [
                'credentials' => new Credentials(getenv('AWS_KEY'), getenv('AWS_SECRET')),
                'version' => 'latest',
                'region' => 'us-east-1'
            ]
        );
    }

    public function add(array $data): void
    {
        $this->client->putItem($data);
    }

    public function get(array $params)
    {
        return $this->client->getItem($params);
    }
}