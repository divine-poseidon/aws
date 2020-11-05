<?php


namespace app\notifier\clients;


use app\notifier\interfaces\ClientInterface;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PhpMailerClient extends PHPMailer implements ClientInterface
{
    public static function composeFromArray(array $params): self
    {
        $sender = getenv('SENDER_EMAIL') === false ? 'piskovyi.dmytro@gmail.com' : getenv('SENDER_EMAIL');
        $senderName = getenv('SENDER_NAME') === false ? 'Piskovyi Dmytro' : getenv('SENDER_NAME');

        if(isset($params['sender'])){
            $sender = $params['sender'];
        }

        if(isset($params['senderName'])){
            $senderName = $params['senderName'];
        }

        $mail = new self();
        $mail->setFrom($sender, $senderName);
        $mail->addAddress($params['recipientEmail']);
        $mail->Subject = $params['subject'];
        $mail->Body = $params['htmlBody'];

        if(isset($params['attachment'])){
            $mail->addAttachment($params['attachment']['filePath'], '', self::ENCODING_BASE64, $params['attachment']['contentType']);
        }

        return $mail;
    }

    public function getMimeMessage(): ?string
    {
        try {
            $this->preSend();
            return $this->getSentMIMEMessage();
        } catch (Exception $e) {
            echo $e->errorMessage();
        }
    }

    public function sendEmail(): bool
    {
        try {
            $this->send();
        } catch (Exception $e) {
            echo $e->errorMessage();
        }
    }
}