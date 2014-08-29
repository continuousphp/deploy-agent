<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 21/08/14
 * Time: 14:06
 */

namespace CphpAgent\Service;

use Zend\Mail;

class MailSender
{
    public static function send($message, $to)
    {
        $mail = new Mail\Message();
        $mail->setBody($message)
            ->setFrom('deploy-agent@continuousphp.com')
            ->addTo($to)
            ->setSubject('Deployment information');

        //TODO change to smtp
        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }
} 