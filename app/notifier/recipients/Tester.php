<?php


namespace app\notifier\recipients;


use app\notifier\interfaces\MailableInterface;

class Tester implements MailableInterface
{

    public function getEmailAddress(): string
    {
        return getenv('TESTER_EMAIL') === false ? 'piskovyi.dmytro@gmail.com' : getenv('TESTER_EMAIL');
    }

    public function getName(): string
    {
        return getenv('TESTER_NAME') === false ? 'Piskovyi Dmytro' : getenv('TESTER_NAME');
    }
}