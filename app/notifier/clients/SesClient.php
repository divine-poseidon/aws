<?php


namespace app\notifier\clients;


use app\notifier\interfaces\ClientInterface;

class SesClient extends \Aws\Ses\SesClient implements ClientInterface
{
    public array $emailParams;

    public function __construct(array $params)
    {
        if(isset($params['emailParams'])){
            $this->emailParams = $params['emailParams'];
        }
        parent::__construct($params);
    }


    public function sendEmail()
    {
        parent::sendEmail($this->emailParams);
    }
}