<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

namespace App\Action\Doctor;

use App\Action\BaseAction;
use App\Entity\Doctor;
use App\Form\ProfileType;
use App\Manager\DoctorManager;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;


class Patch extends BaseAction
{

    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * Update constructor.
     * @param DoctorManager $dm
     */
    public function __construct(DoctorManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * Doctor Profile Update
     *
     * Update the doctor profile with provided information
     *
     * @Rest\Patch("/api/v1/secured/doctor/{guid}")
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
     *     name="doctor",
     *     in="body",
     *     required=true,
     *     @Model(type=ProfileType::class),
     *     description="category should be : SENIOR / JUNIOR"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Doctor resource patch success",
     *     @SWG\Schema(
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(property="firstname", type="string"),
     *          @SWG\Property(property="lastName", type="string"),
     *          @SWG\Property(property="phoneNumber", type="string"),
     *          @SWG\Property(property="address", type="string"),
     *          @SWG\Property(property="region", type="string"),
     *          @SWG\Property(property="category", type="string")
     *     )
     * )
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token / Identity has changed")
     * @SWG\Response(response=404, description="App\\Entity\\Patient object not found by the @ParamConverter annotation.")
     *
     * @SWG\Tag(name="Doctor")
     *
     * @Rest\View(serializerGroups={"profile"})
     * @param Request $request
     * @param Doctor $doctor
     * @return View|FormInterface
     */
    public function __invoke(Request $request, Doctor $doctor)
    {
        $form = $this->createForm(ProfileType::class, $doctor);
        $form->submit($request->request->all(), false);
        if (!$form->isValid()) {

            return $form;
        }

        $this->dm->update($doctor);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Doctor resource patch success',
            [
                'doctor' => $doctor
            ]
        );
    }
}