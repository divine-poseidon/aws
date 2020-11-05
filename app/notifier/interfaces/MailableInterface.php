<?php


namespace app\notifier\interfaces;


interface MailableInterface extends RecipientInterface
{
    public function getEmailAddress(): string;
}