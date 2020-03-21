<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

namespace App\Action\Security;


use App\Action\BaseAction;
use App\ApiEvents\DoctorEvents;
use App\Dto\ResetPasswordRequest;
use App\Event\DoctorEvent;
use App\Form\ResetPasswordRequestType;
use App\Manager\DoctorManager;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class ResetPassword extends BaseAction
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
     * Doctor Reset Password
     *
     * reset password
     *
     * @Rest\Post("/api/v1/security/reset-password")
     *
     * @SWG\Parameter(
     *     name="token",
     *     in="query",
     *     type="string",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @Model(type=ResetPasswordRequestType::class)
     * )
     *
     * @SWG\Response(response=200,description="Password successfully reset")
     * @SWG\Response(response=400, description="Validation Failed / Missing Token Parameter")
     * @SWG\Response(response=401, description="Bad Resetting Token")
     *
     * @SWG\Tag(name="Security")
     *
     * @Rest\View()
     * @param Request $request
     * @return View|FormInterface
     */
    public function __invoke(Request $request)
    {
        if (null === ($token = $request->query->get('token')) or '' === $token) {
            return $this->JsonResponse(
                Response::HTTP_BAD_REQUEST,
                'Missing Token Parameter'
            );
        }

        $doctor = $this->dm->getDoctorByConfirmationToken($token);

        if (null === $doctor) {
            return $this->JsonResponse(
                Response::HTTP_UNAUTHORIZED,
                'Bad Resetting Token'
            );
        }

        $resetPasswordRequest = new ResetPasswordRequest();
        $form = $this->createForm(ResetPasswordRequestType::class, $resetPasswordRequest);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $this->dm->resetPassword($doctor, $resetPasswordRequest);
        $this->dispatcher->dispatch(new DoctorEvent($doctor), DoctorEvents::DOCTOR_RESET_PASSWORD_SUCCESS);

        return $this->JsonResponse(
            Response::HTTP_OK,
            'Password successfully reset'
        );
    }
}