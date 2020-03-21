<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/
namespace App\Action\Doctor;

use App\Action\BaseAction;
use App\Manager\DoctorManager;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;



class Me  extends BaseAction
{
    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * Me constructor.
     * @param DoctorManager $dm
     */
    public function __construct(DoctorManager $dm)
    {
        $this->dm = $dm;
    }


    /**
     * Doctor Profile Retrieval
     *
     * Retrieve Doctor Profile
     *
     * @Rest\Get("/api/v1/secured/doctor/me")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Authorization"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * )
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token")
     *
     * @SWG\Tag(name="Doctor")
     *
     * @Rest\View(serializerGroups={"profile"})
     * @return View
     */
    public function __invoke()
    {

        $doctor = $this->dm->getCurrentDoctor();

        return $this->JsonResponse(
            Response::HTTP_OK,
            'Success',
            $doctor
        );
    }

}