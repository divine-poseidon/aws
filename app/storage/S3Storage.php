<?php


namespace app\storage;


use app\UploadedPhoto;
use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\S3\S3Client;

class S3Storage implements StorageInterface
{
    private S3Client $client;

    public function __construct()
    {
        $this->client = new S3Client(
            [
                'version' => 'latest',
                'credentials' => new Credentials(getenv('AWS_KEY'), getenv('AWS_SECRET')),
                'region' => 'us-east-1'
            ]);
    }

    public function add(array $data): void
    {
        $this->client->putObject($data);
    }

    public function get(array $params)
    {
        return $this->client->getObject($params);
    }

    public function getStoredPhoto(UploadedPhoto $uploadedPhoto): array
    {
        $result = $this->get([
            'Bucket' => $uploadedPhoto->bucketName,
            'Key' => $uploadedPhoto->photoName,
        ]);

        file_put_contents('/tmp/'. $uploadedPhoto->photoName, $result['Body']);

        return ['contentType' => $result['ContentType'], 'filePath' => '/tmp/'. $uploadedPhoto->photoName];

    }
}