<?php

namespace App\EventListener;

use App\Entity\Doctor;
use App\Manager\DoctorManager;
use App\Service\MailerInterface;
use App\Util\Tools;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig_Environment;

/**
 * Class InformerAdmin
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
 **/
class NotifyDoctorEventListener
{

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Twig_Environment
     */
    private $templating;

    /**
     * NotifyDoctorEventListener constructor.
     *
     * @param MailerInterface $mailer
     * @param Environment $templating
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

            $this->mailer->sendEmail(
                "Bienvenue sur la plateforme Maabaadhna",
                $doctor->getEmail(),
                $this->templating->render('emails/security/notify-doctor.html.twig', [
                    'fullName' => $doctor->getFullname(),
                    'email' => $doctor->getEmail(),
                    'password' => $doctor->getPlainPassword()
                ])
            );

            $doctor->eraseCredentials();
        }
    }
}
