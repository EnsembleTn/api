<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

namespace App\Action\Doctor;


use App\Action\BaseAction;
use App\Dto\ChangePasswordRequest;
use App\Form\ChangePasswordRequestType;
use App\Manager\DoctorManager;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;

class ChangePassword extends BaseAction
{
    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * Register constructor.
     *
     * @param DoctorManager $dm
     */
    public function __construct(DoctorManager $dm )
    {
        $this->dm = $dm;
    }

    /**
     * Doctor Password Change
     *
     * Change doctor account's password
     *
     * @Rest\Put("/api/v1/secured/doctor/change-password")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Authorization"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @Model(type=ChangePasswordRequestType::class)
     * )
     *
     * @SWG\Response(response=200,description="Password successfully changed")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found / Invalid JWT Token / Bad Credentials / You can't choose the same password"
     * )
     *
     * @SWG\Tag(name="Doctor")
     *
     * @Rest\View()
     * @param Request $request
     * @return View|FormInterface
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $changePasswordRequest = new ChangePasswordRequest();
        $form = $this->createForm(ChangePasswordRequestType::class, $changePasswordRequest);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $doctor = $this->dm->getCurrentDoctor();
        $passwordChanged = $this->dm->changePassword($doctor, $changePasswordRequest);

        if ($passwordChanged === null) {
            return $this->JsonResponse(
                Response::HTTP_UNAUTHORIZED,
                'You can\'t choose the same password'
            );
        } elseif (!$passwordChanged) {
            return $this->JsonResponse(
                Response::HTTP_UNAUTHORIZED,
                'Bad Credentials'
            );
        }


        return $this->JsonResponse(
            Response::HTTP_OK,
            'Password successfully changed'
        );
    }
}