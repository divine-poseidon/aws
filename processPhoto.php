<?php
use app\PhotoProcessor;
use app\Rekognition;
use app\UploadedPhoto;

require_once 'functionBasis.php';

return static function (array $event) {
    $uploadedPhoto = UploadedPhoto::createFromEvent($event);
    $uploadedPhotoLabels = (new Rekognition())->detectLabelsFromUploadedPhoto($uploadedPhoto);

    return [
        'present' => PhotoProcessor::checkIfDogIsPresent($uploadedPhotoLabels),
        'uploadedPhoto' => $uploadedPhoto,
        'labels' => $uploadedPhotoLabels
    ];
};

