<?php

use app\storage\DynamoDbStorage;
use app\UploadedPhoto;

require_once 'functionBasis.php';

return static function (array $params) {
    $uploadedPhoto = new UploadedPhoto($params['uploadedPhoto']['photoName'], $params['uploadedPhoto']['bucketName']);
    $data = [
        'TableName' => 'Pictures',
        'Item' => [
            'Picture' => [
                'S' => $uploadedPhoto->photoName
            ],
            'Labels' => [
                'S' => json_encode($params['labels'])
            ]
        ]
    ];

    (new DynamoDbStorage())->add($data);
};