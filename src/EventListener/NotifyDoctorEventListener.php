<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

namespace App\EventListener;


use App\Entity\Doctor;
use App\Service\MailerInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NotifyDoctorEventListener
{

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $templating;


    /**
     * NotifyDoctorEventListener constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer, Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;

    }

    /**
     * @param LifecycleEventArgs $args
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $doctor = $args->getObject();
        if ($doctor instanceof Doctor) {
            $url = $this->mailer->getFrontEndServerUrl() . '/login';
            $this->mailer->sendEmail(
                "Account Information",
                $doctor->getEmail(),
                $this->templating->render('emails/security/notify-doctor.html.twig', [
                    'fullName' => $doctor->getFullname(),
                    'email' => $doctor->getEmail(),
                    'password' => $doctor->getPlainPassword(),
                    'url' => $url
                ])
            );
            $doctor->eraseCredentials();
        }
        return;


    }
}
