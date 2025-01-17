<?php

use app\AwsServicesConfig;
use app\notifier\clients\PhpMailerClient;
use app\notifier\clients\SesClient;
use app\notifier\notifications\DogIsAbsentEmailNotification;
use app\notifier\recipients\Tester;
use app\storage\S3Storage;
use app\UploadedPhoto;
use Aws\Credentials\Credentials;

require_once 'functionBasis.php';
return static function (array $params) {
    $uploadedPhoto = new UploadedPhoto($params['uploadedPhoto']['photoName'], $params['uploadedPhoto']['bucketName']);
    $recipientEmail = (new Tester())->getEmailAddress();
    $notification = new DogIsAbsentEmailNotification();
    $s3Photo = (new S3Storage())->getStoredPhoto($uploadedPhoto);
    $phpMailer = PhpMailerClient::composeFromArray([
        'recipientEmail' => $recipientEmail,
        'subject' => $notification->getSubject(),
        'htmlBody' => $notification->getHtmlBody(),
        'attachment' => [
            'filePath' => $s3Photo['filePath'],
            'contentType' => $s3Photo['contentType']
        ]]);


    $sesClient = new SesClient(array_merge(AwsServicesConfig::getServicesParams(),
            [
                'emailParams' => [
                    'RawMessage' => [
                        'Data' => $phpMailer->getMimeMessage()
                    ],
                ]
            ])
    );
    $sesClient->sendRawEmail($sesClient->emailParams);
    unlink($s3Photo['filePath']);
};