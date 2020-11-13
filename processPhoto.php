<?php
use app\Rekognition;
use app\UploadedPhoto;

require_once 'functionBasis.php';

return static function (array $event) {
    $uploadedPhoto = UploadedPhoto::createFromEvent($event);
    $uploadedPhotoLabels = (new Rekognition())->detectLabelsFromUploadedPhoto($uploadedPhoto);

    return [
        'present' => Rekognition::checkIfDogIsPresentOnPhoto($uploadedPhotoLabels),
        'uploadedPhoto' => $uploadedPhoto,
        'labels' => $uploadedPhotoLabels
    ];
};

