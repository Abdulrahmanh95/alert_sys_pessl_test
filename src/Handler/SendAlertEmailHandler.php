<?php
namespace App\Handler;

use App\Domain\Repository\AlertRepository;
use App\Message\Mail\SendAlertEmail;
use PHPMailer\PHPMailer\Exception;

class SendAlertEmailHandler extends BaseEmailHandler
{
    /**
     * @var AlertRepository
     */
    private $alertRepository;

    public function __construct(AlertRepository $alertRepository)
    {
        $this->alertRepository = $alertRepository;
    }

    public function __invoke(SendAlertEmail $message)
    {
        $mail = $this->instantiateMail();

        // Recipients
        $mail->setFrom('kobybryant.h@gmail.com', 'Aboodz');
        $mail->addAddress('abd.alrahmanh.1995@gmail.com', 'Abdulrahman Hashem');     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $message->getSubject();
        $mail->Body    = $message->getMessage();

        try {
            $mail->send();
            $userId = $message->getUser()['id'];
            foreach ($message->getHandledTypes() as $type) {
                $this->alertRepository->setSent($type, $userId);
            }
        } catch (Exception $e) {
            echo "Something went wrong..";
        }
    }
}