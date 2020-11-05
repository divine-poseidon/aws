<?php
declare(strict_types = 1);

use app\PhotoProcessor;
use app\Rekognition;
use app\storage\S3Storage;
use app\UploadedPhoto;
use Aws\S3\S3Client;

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->required(['AWS_KEY', 'AWS_SECRET']);
$dotenv->load();


return static function (array $event) {
    $uploadedPhotos = UploadedPhoto::createObjectsFromEvent($event);
    $uploadedPhotosLabels = (new Rekognition($uploadedPhotos))->detectLabelsFromUploadedPhotos();
    (new PhotoProcessor($uploadedPhotosLabels))->processLabels();
};

