<?php

namespace App\EventListener;

use App\ApiEvents\DoctorEvents;
use App\Entity\Doctor;
use App\Event\DoctorEvent;
use App\Manager\DoctorManager;
use DateTime;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Override Lexik Authentication Success Response
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class AuthenticationSuccessListener
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * AuthenticationSuccessListener constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param DoctorManager $dm
     */
    public function __construct(EventDispatcherInterface $dispatcher, DoctorManager $dm)
    {
        $this->dispatcher = $dispatcher;
        $this->dm = $dm;
    }

    /**
     * Authentication Success
     *
     * @param AuthenticationSuccessEvent $event
     * @throws Exception
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();

        if (!($user instanceof Doctor)) {
            return;
        }

        if ($user->isActive() == true) {

            $doctorEvent = new DoctorEvent($user);
            $this->dispatcher->dispatch($doctorEvent, DoctorEvents::DOCTOR_LOGIN);

            $data['firstTimeConnection'] = $user->getLastLogin() ? false: true;
            $data['token'] = $event->getData()['token'];


            $event->setData([
                'code' => $event->getResponse()->getStatusCode(),
                'message' => 'Authentication Success',
                'payload' => $data
            ]);

            $user->getLastLogin() ?:$user->setLastLogin(new DateTime());
            $this->dm->updateDoctor($user);

        } else {
            $event->getResponse()->setStatusCode(Response::HTTP_LOCKED);
            $event->setData([
                'code' => Response::HTTP_LOCKED,
                'message' => 'Account Locked'
            ]);
        }
    }
}