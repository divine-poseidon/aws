<?php


namespace app\notifier\notifiers;


use app\notifier\interfaces\ClientInterface;
use app\notifier\interfaces\NotifierInterface;

class EmailNotifier implements NotifierInterface
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function notify(): void
    {
        $this->client->sendEmail();
    }
}