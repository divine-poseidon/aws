<?php


namespace app;


use app\notifier\clients\PhpMailerClient;
use app\notifier\clients\SesClient;
use app\notifier\DogIsAbsentEmailNotification;
use app\notifier\EmailNotifier;
use app\notifier\recipients\Tester;
use app\storage\DynamoDbStorage;
use app\storage\S3Storage;
use app\storage\StorageInterface;
use Aws\Credentials\Credentials;
use Aws\Result;

class PhotoProcessor
{
    private const DOG_LABEL_NAME = 'Dog';

    /**@var Result[] */
    private array $uploadedPhotosLabels;

    public function __construct(array $uploadedPhotosLabels)
    {
        $this->uploadedPhotosLabels = $uploadedPhotosLabels;
    }

    public function processLabels(): void
    {
        foreach ($this->uploadedPhotosLabels as $uploadedPhotoLabels) {
            $dogPresence = false;
            foreach ($uploadedPhotoLabels['labels'] as $labels) {
                if ($this->checkDogPresence($labels['Name'])) {
                    $dogPresence = true;
                    $this->processDogIsPresent(new DynamoDbStorage(), $uploadedPhotoLabels);
                    break;
                }
            }
            if($dogPresence === false){
                $this->processDogIsAbsent($uploadedPhotoLabels);
            }
        }
    }

    private function checkDogPresence(string $label): bool
    {
        return $label === self::DOG_LABEL_NAME;
    }

    private function processDogIsPresent(StorageInterface $storage, array $uploadedPhotoLabels): void
    {
        $data = [
            'TableName' => 'Pictures',
            'Item' => [
                'Picture' => [
                    'S' => $uploadedPhotoLabels['uploadedPhoto']->photoName,
                ],
                'Labels' => [
                    'S' => json_encode($uploadedPhotoLabels['labels'])
                ]
            ]
        ];

        $storage->add($data);
    }

    private function processDogIsAbsent($uploadedPhotoLabels): void
    {
        $recipientEmail = (new Tester())->getEmailAddress();
        $notification = new DogIsAbsentEmailNotification();
        $s3Photo = (new S3Storage())->getStoredPhoto($uploadedPhotoLabels['uploadedPhoto']);
        $phpMailer = PhpMailerClient::composeFromArray([
            'recipientEmail' => $recipientEmail,
            'subject' => $notification->getSubject(),
            'htmlBody' => $notification->getHtmlBody(),
            'attachment' => [
                'filePath' => $s3Photo['filePath'],
                'contentType' => $s3Photo['contentType']
            ]]);


        $sesClient = new SesClient([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => new Credentials(getenv('AWS_KEY'), getenv('AWS_SECRET')),
            'emailParams' =>
                [
                    'RawMessage' => [
                        'Data' => $phpMailer->getMimeMessage()
                    ],
                ]
        ]);
        $sesClient->sendRawEmail($sesClient->emailParams);
        unlink($s3Photo['filePath']);
    }
}