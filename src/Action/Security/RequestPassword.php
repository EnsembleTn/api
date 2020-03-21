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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RequestPassword extends BaseAction
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * RequestPassword constructor.
     * @param DoctorManager $dm
     * @param ValidatorInterface $validator
     */
    public function __construct(DoctorManager $dm, ValidatorInterface $validator, EventDispatcherInterface $dispatcher)
    {
        $this->validator = $validator;
        $this->dm = $dm;
        $this->dispatcher=$dispatcher;
    }

    /**
     * Doctor Request New Password
     *
     * request new password : email sending
     *
     * @Rest\Get("/api/v1/security/request-password")
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     required=true
     * )
     *
     * @SWG\Response(response=200,description="Request password email successfully sent")
     * @SWG\Response(response=400, description="Validation Failed / Missing Email Parameter")
     *
     * @SWG\Tag(name="Security")
     *
     * @Rest\View()
     * @param Request $request
     * @return View
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        if (null === ($email = $request->query->get('email')) || '' === $email) {
            return $this->JsonResponse(
                Response::HTTP_BAD_REQUEST,
                'Missing Email Parameter'
            );
        }

        $errors = $this->validator->validate($email, new Assert\Email([
            'message' => 'The provided query string is not a valid email address.'
        ]));

        if (0 !== count($errors)) {
            return $this->JsonResponse(
                Response::HTTP_BAD_REQUEST,
                'Validation Failed',
                ['errors' => $errors[0]->getMessage()]
            );
        }

        $doctor = $this->dm->getDoctorByEmail($email);
        if (null !== $doctor) {
            if ($this->dm->requestPassword($doctor) == true) {
                $this->dispatcher->dispatch(new DoctorEvent($doctor), DoctorEvents::DOCTOR_REQUEST_PASSWORD);

            }
            return $this->JsonResponse(
                Response::HTTP_OK,
                'Request password email successfully sent'
            );
        }

        return $this->JsonResponse(
            Response::HTTP_NOT_FOUND,
            'This email does not exist'
        );
    }
}