<?php


namespace app;


use Aws\Credentials\Credentials;
use Aws\Rekognition\RekognitionClient;
use Aws\Result;

class Rekognition
{
    private RekognitionClient $rekognitionClient;

    /** @var $uploadedPhotos UploadedPhoto[] */
    private array $uploadedPhotos;

    public function __construct(array $uploadedPhotos)
    {
        $this->rekognitionClient = new RekognitionClient(
            [
                'version' => 'latest',
                'region' => 'us-east-1',
                'credentials' => new Credentials(getenv('AWS_KEY'), getenv('AWS_SECRET'))
            ]);
        $this->uploadedPhotos = $uploadedPhotos;
    }

    /**
     * @return Result[]
     */
    public function detectLabelsFromUploadedPhotos(): array
    {
        $result = [];

        foreach ($this->uploadedPhotos as $uploadedPhoto) {
            $params = [
                'Image' => [
                    'S3Object' => [
                        'Bucket' => $uploadedPhoto->bucketName,
                        'Name' => $uploadedPhoto->photoName,
                    ],
                ],
                'MinConfidence' => 75,
            ];

            $result[] = [
                'uploadedPhoto' => $uploadedPhoto,
                'labels' => $this->rekognitionClient->detectLabels($params)['Labels']
            ];
        }

        return $result;
    }
}