<?php


namespace app\notifier\notifications;


use app\notifier\interfaces\EmailNotificationInterface;

class DogIsAbsentEmailNotification implements EmailNotificationInterface
{

    public function getSubject(): string
    {
        return 'Dog is absent';
    }

    public function getHtmlBody(): string
    {
        return 'In the following picture dog is absent';
    }
}