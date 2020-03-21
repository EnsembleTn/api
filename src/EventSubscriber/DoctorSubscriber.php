<?php
namespace App\EventSubscriber;

use App\ApiEvents\DoctorEvents;
use App\Event\DoctorEvent;
use App\Service\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

class DoctorSubscriber implements EventSubscriberInterface
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
     * UserSubscriber constructor.
     * @param MailerInterface $mailer
     * @param Environment $templating
     */
    public function __construct(MailerInterface $mailer, Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;

    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            DoctorEvents::DOCTOR_REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            DoctorEvents::DOCTOR_REGISTRATION_CONFIRM => 'onRegistrationConfirm',
            DoctorEvents::DOCTOR_REQUEST_PASSWORD => 'onRequestPassword',
            DoctorEvents::DOCTOR_RESET_PASSWORD_SUCCESS => 'onResetPasswordSuccess',
        );
    }

    /**
     * Actions to perform after doctor registration success goes here
     *
     * @param DoctorEvent $doctorEvent
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onRegistrationSuccess(DoctorEvent $doctorEvent)
    {
        $doctor = $doctorEvent->getDoctor();

        // send confirmation email
        $url = $this->mailer->getFrontEndServerUrl().'/register/confirmation/'.$doctor->getConfirmationToken();
        $this->mailer->sendEmail(
            'Welcome',
            $doctor->getEmail(),
            $this->templating->render('emails/security/register.html.twig', [
                'doctor' => $doctor,
                'url' => $url
            ])
        );
    }

    /**
     * Actions to perform after client registration confirm goes here
     *
     * @param DoctorEvent $doctorEvent
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onRegistrationConfirm(DoctorEvent $doctorEvent)
    {
        $doctor = $doctorEvent->getDoctor();

        // send reset password success email
        $this->mailer->sendEmail(
            'Account Successfully Confirmed',
            $doctor->getEmail(),
            $this->templating->render('emails/security/confirm-registration.html.twig', [
                'doctor' => $doctor
            ])
        );
    }

    /**
     * Actions to perform after doctor request password goes here
     *
     * @param DoctorEvent $doctorEvent
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onRequestPassword(DoctorEvent $doctorEvent)
    {
        $doctor = $doctorEvent->getDoctor();
        $url = $this->mailer->getFrontEndServerUrl().'/password/reset/'.$doctor->getConfirmationToken();
        // send request password email
        $this->mailer->sendEmail(
            "New Password Requested At ". $doctor->getPasswordRequestedAt()->format('H:i d/m/Y'),
            $doctor->getEmail(),
            $this->templating->render('emails/security/request-password.html.twig', [
                'doctor' => $doctor,
                'url' => $url
            ])
        );
    }

    /**
     * Actions to perform after doctor reset password success goes here
     *
     * @param DoctorEvent $doctorEvent
     * @throws \Exception
     */
    public function onResetPasswordSuccess(DoctorEvent $doctorEvent)
    {
        $doctor = $doctorEvent->getDoctor();

        // send reset password success email
        $this->mailer->sendEmail(
            'Password Successfully Reset',
            $doctor->getEmail(),
            $this->templating->render('emails/security/reset-password.html.twig', [
                'doctor' => $doctor
            ])
        );
    }


}