<?php


namespace app;

use Aws\Rekognition\RekognitionClient;

class Rekognition
{
    private const DOG_LABEL_NAME = 'Dog';
    private RekognitionClient $rekognitionClient;

    public function __construct()
    {
        $this->rekognitionClient = new RekognitionClient(AwsServicesConfig::getServicesParams());
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

    public static function checkIfDogIsPresentOnPhoto(array $labels): bool
    {
        foreach ($labels as $label) {
            if ($label['Name'] === self::DOG_LABEL_NAME) {
                return true;
            }
        }
        return false;
    }
}