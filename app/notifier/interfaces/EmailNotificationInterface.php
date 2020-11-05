<?php


namespace app\notifier\interfaces;


interface EmailNotificationInterface extends NotificationInterface
{
    public function getSubject(): string;
    public function getHtmlBody(): string;
}