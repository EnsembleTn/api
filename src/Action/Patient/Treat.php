<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use App\Manager\DoctorManager;
use App\Manager\PatientManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Treat
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Treat extends BaseAction
{
    /**
     * Treat patient
     *
     * Treat first on hold patient in queue
     *
     * @Rest\Get("/api/v1/secured/treat-patient")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     description="Api Authorization key",
     *     default="Bearer TOKEN"
     * )
     * @SWG\Response(response=200, description="Patient resource get success")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token / Identity has changed")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View(serializerGroups={"treat"})
     * @param Request $request
     * @param PatientManager $pm
     * @param DoctorManager $dm
     * @return View
     */
    public function __invoke(Request $request, PatientManager $pm, DoctorManager $dm)
    {
        $doctor = $dm->getCurrentDoctor();

        // get first on hold patient in queue
        $patient = $pm->treat($doctor);

        if ($patient) {
            // automatically update patient status to IN_PROGRESS
            $doctor->isEmergencyDoctor() ? $patient->setEmergencyStatus(Patient::STATUS_IN_PROGRESS) : $patient->setStatus(Patient::STATUS_IN_PROGRESS);
            $pm->update($patient);
        }

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Patient resource get success',
            [
                'patient' => $patient ? $patient : []
            ]
        );
    }
}
