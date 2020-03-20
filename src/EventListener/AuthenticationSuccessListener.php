<?php

namespace App\EventListener;

use App\ApiEvents\DoctorEvents;
use App\Entity\Doctor;
use App\Event\DoctorEvent;
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
     * AuthenticationSuccessListener constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Authentication Success
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();

        if (!($user instanceof Doctor)) {
            return;
        }

        if ($user->isActive() == true) {

            $event = new DoctorEvent($user);
            $this->dispatcher->dispatch($event, DoctorEvents::DOCTOR_LOGIN);

            $event->setData([
                'code' => $event->getResponse()->getStatusCode(),
                'message' => 'Authentication Success',
                'payload' => $event->getData()
            ]);

        } else {
            $event->getResponse()->setStatusCode(Response::HTTP_LOCKED);
            $event->setData([
                'code' => Response::HTTP_LOCKED,
                'message' => 'Account Locked'
            ]);
        }
    }
}