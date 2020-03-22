<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Manager\PatientManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GetAll
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class GetAll extends BaseAction
{
    /**
     * Get patient list
     *
     * Get all patient without questions responses (only guid , firstName, lastName, phoneNumber and status will be provided)
     *
     * @Rest\Get("/api/v1/secured/patient")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     description="Api Authorization key",
     *     default="Bearer TOKEN"
     * )
     *
     * @SWG\Response(response=200, description="Patients resources get success")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token / Identity has changed")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View(serializerGroups={"patient-list"})
     * @param Request $request
     * @param PatientManager $pm
     * @return View
     */
    public function __invoke(Request $request, PatientManager $pm)
    {

        $patients = $pm->getAll();

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Patients resources get success',
            [
                'patients' => $patients
            ]
        );
    }
}
