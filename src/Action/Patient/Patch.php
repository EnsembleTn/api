<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Form\PatientUpdateType;
use App\Manager\DoctorManager;
use App\Manager\PatientManager;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Patch
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Patch extends BaseAction
{
    /**
     * Patch patient resource
     *
     * Update an existing patient ( used to update status or flag only )
     *
     * @Rest\Patch("/api/v1/secured/patient/{guid}")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     description="Api Authorization key",
     *     default="Bearer TOKEN"
     * )
     * @SWG\Parameter(
     *     name="patient",
     *     in="body",
     *     required=true,
     *     @Model(type=PatientUpdateType::class),
     *     description="Doctor can update only the flag to one of the following options : STABLE / SUSPECT / URGENT.
    <br> By updating the flag field  the patient case status field will be automatically set to CLOSED and emergencyStatus field will be automatically set to ON_HOLD.
    <br> **to update flag field remove the emergencyStatus filed from the request body.**
    <br> Emergency doctor can update only the emergencyStatus field to CLOSED.
    <br> **to update emergencyStatus field to CLOSED remove the flag field from the request body.**
    <br> Fields status & emergencyStatus will be automatically set to IN_PROGRESS once **GET /api/v1/secured/treat-patient** is invoked."
     * )
     *
     * @SWG\Response(response=200, description="Patient resource patch success")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token / Identity has changed")
     * @SWG\Response(response=404, description="App\\Entity\\Patient object not found by the @ParamConverter annotation.")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View(serializerGroups={"patient"})
     * @param Request $request
     * @param Patient $patient
     * @param PatientManager $pm
     * @param DoctorManager $dm
     * @return View|FormInterface
     */
    public function __invoke(Request $request, Patient $patient, PatientManager $pm, DoctorManager $dm)
    {
        try {
            $dm->canUpdatePatient($patient);
        } catch (Exception $e) {
            return $this->jsonResponse(
                Response::HTTP_BAD_REQUEST,
                $e->getMessage()
            );
        }

        $form = $this->createForm(PatientUpdateType::class, $patient, ['doctor' => $dm->getCurrentDoctor()]);
        $form->submit($request->request->all(), false);
        if (!$form->isValid()) {
            return $form;
        }

        $pm->update($patient);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Patient resource patch success',
            [
                'patient' => $patient
            ]
        );
    }
}
