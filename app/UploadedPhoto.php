<?php


namespace app;


class UploadedPhoto
{
    public string $photoName;
    public string $bucketName;

    public function __construct(string $photoName, string $bucketName)
    {
        $this->photoName = $photoName;
        $this->bucketName = $bucketName;
    }

    public static function createFromEvent(array $event): UploadedPhoto
    {
        $eventRecords = reset($event['Records']);
        $s3Object = $eventRecords['s3'];
        $imageName = $s3Object['object']['key'];
        $bucketName = $s3Object['bucket']['name'];

        return new UploadedPhoto($imageName, $bucketName);
    }
}