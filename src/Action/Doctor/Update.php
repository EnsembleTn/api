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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;


class Update extends BaseAction
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
     * @Rest\Patch("/api/v1/secured/doctor/update")
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
     *     @Model(type=ProfileType::class)
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Profile updated successfully",
     *     @SWG\Schema(
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(property="firstname", type="string"),
     *          @SWG\Property(property="lastName", type="string"),
     *          @SWG\Property(property="phoneNcmber", type="string"),
     *          @SWG\Property(property="address", type="string"),
     *          @SWG\Property(property="region", type="string"),
     *          @SWG\Property(property="category", type="string"),
     *     )
     * )
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token")
     *
     * @SWG\Tag(name="Doctor")
     *
     * @Rest\View(serializerGroups={"profile"})
     * @param Request $request
     * @return View|FormInterface
     */
    public function __invoke(Request $request)
    {
        $doctor = $this->dm->getCurrentDoctor();
        $form = $this->createForm(ProfileType::class, $doctor);

        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {

            return $form;
        }

        $this->dm->updateDoctor($doctor);

        return $this->JsonResponse(
            Response::HTTP_OK,
            'Profile updated successfully',
            $doctor
        );
    }
}