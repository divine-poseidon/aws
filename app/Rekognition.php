<?php


namespace app;


use Aws\Credentials\Credentials;
use Aws\Rekognition\RekognitionClient;

class Rekognition
{
    private RekognitionClient $rekognitionClient;

    public function __construct()
    {
        $this->rekognitionClient = new RekognitionClient(
            [
                'version' => 'latest',
                'region' => 'us-east-1',
                'credentials' => new Credentials(getenv('AWS_KEY'), getenv('AWS_SECRET'))
            ]);
    }

    public function detectLabelsFromUploadedPhoto(UploadedPhoto $uploadedPhoto): array
    {
        $params = [
            'Image' => [
                'S3Object' => [
                    'Bucket' => $uploadedPhoto->bucketName,
                    'Name' => $uploadedPhoto->photoName,
                ],
            ],
            'MinConfidence' => 75,
        ];


        return $this->rekognitionClient->detectLabels($params)['Labels'];
    }
}