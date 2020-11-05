<?php


namespace app;


class UploadedPhoto
{
    public string $bucketName;
    public string $photoName;

    public function __construct(string $imageName, string $bucketName)
    {
        $this->photoName = $imageName;
        $this->bucketName = $bucketName;
    }

    /**
     * @param array $event
     * @return UploadedPhoto[]
     */
    public static function createObjectsFromEvent(array $event): array
    {
        $result = [];

        foreach ($event['Records'] as $recordIndex => $recordData) {
            $s3Object = $event['Records'][$recordIndex]['s3'];
            $imageName = $s3Object['object']['key'];
            $bucketName = $s3Object['bucket']['name'];
            $result[] = new UploadedPhoto($imageName, $bucketName);
        }

        return $result;
    }
}