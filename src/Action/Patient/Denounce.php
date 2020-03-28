<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use App\Manager\DoctorManager;
use App\Manager\PatientManager;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Denounce
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Denounce extends BaseAction
{
    /**
     * Denounce patient
     *
     * Add patient to denounced list
     *
     * @Rest\Patch("/api/v1/secured/denounce-patient/{guid}")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     description="Api Authorization key",
     *     default="Bearer TOKEN"
     * )
     * @SWG\Response(response=200, description="Patient denounced successfully")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token / Identity has changed")
     * @SWG\Response(response=403, description="Forbidden")
     * @SWG\Response(response=404, description="App\\Entity\\Patient object not found by the @ParamConverter annotation.")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View()
     * @param Request $request
     * @param Patient $patient
     * @param PatientManager $pm
     * @param DoctorManager $dm
     * @return View
     * @throws Exception
     */
    public function __invoke(Request $request, Patient $patient, PatientManager $pm, DoctorManager $dm)
    {

        try {
            $dm->canDenounce($patient);
        } catch (Exception $e) {
            return $this->jsonResponse(
                Response::HTTP_FORBIDDEN,
                $e->getMessage()
            );
        }

        $patient->setDenounced(1);
        $patient->setStatus(Patient::STATUS_CLOSED);
        $patient->setDoctor($dm->getCurrentDoctor());

        $pm->update($patient);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Patient denounced successfully'
        );
    }
}
