<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

class Mailer implements MailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer $mailerService
     * @param ParameterBagInterface $params
     */
    public function __construct(\Swift_Mailer $mailerService, ParameterBagInterface $params)
    {
        $this->mailer = $mailerService;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function sendEmail(string $subject, $recipient, $body): void
    {
        $email =(new \Swift_Message())
            ->setSubject($subject)
            ->setTo($recipient)
            ->setBody($body)
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setFrom($this->params->get('mailer_user'));

        $this->mailer->send($email);
    }

    /**
     * {@inheritDoc}
     */
    public function sendEmailWithAttachment(string $subject, $recipient, $body, $attachment): void
    {
        $email = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($recipient)
            ->setBody($body)
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setFrom($this->params->get('mailer_user'));
        //->attach(\Swift_Attachment::newInstance())

        $this->mailer->send($email);

    }


    /**
     * {@inheritdoc}
     */
    public function getMailerUser() : string
    {
        return $this->params->get('mailer_user');
    }
    public function getFrontEndServerUrl():string
    {
        return $this->params->get('front_end_server');
    }
    public function getBackEndServerUrl():string
    {
        return $this->params->get('back_end_server');
    }
}