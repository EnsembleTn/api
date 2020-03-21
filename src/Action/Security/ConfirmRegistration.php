<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

namespace App\Action\Security;


use App\Action\BaseAction;
use App\ApiEvents\DoctorEvents;
use App\Event\DoctorEvent;
use App\Manager\DoctorManager;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ConfirmRegistration extends BaseAction
{

    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Register constructor.
     *
     * @param DoctorManager $dm
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(DoctorManager $dm, EventDispatcherInterface $dispatcher)
    {
        $this->dm = $dm;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Doctor Confirm Registration
     *
     * confirm doctor account
     *
     * @Rest\Get("/api/v1/security/confirm-registration")
     *
     * @SWG\Parameter(
     *     name="token",
     *     in="query",
     *     type="string",
     *     required=true
     * )
     *
     * @SWG\Response(response=200,description="Account successfully confirmed")
     * @SWG\Response(response=400, description="Missing Token Parameter")
     * @SWG\Response(response=401, description="Bad Confirmation Token")
     *
     * @SWG\Tag(name="Security")
     *
     * @Rest\View()
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request)
    {
        if (null === ($token = $request->query->get('token'))) {
            return $this->JsonResponse(
                Response::HTTP_BAD_REQUEST,
                'Missing Token Parameter'
            );
        }

        $doctor = $this->dm->getDoctorByConfirmationToken($token);

        if (null == $doctor) {
            return $this->JsonResponse(
                Response::HTTP_UNAUTHORIZED,
                'Bad Confirmation Token'
            );
        }

        $this->dm->confirmAccount($doctor);
        $this->dispatcher->dispatch(new DoctorEvent($doctor), DoctorEvents::DOCTOR_REGISTRATION_CONFIRM);

        return $this->JsonResponse(
            Response::HTTP_OK,
            'Account successfully confirmed'
        );
    }
}