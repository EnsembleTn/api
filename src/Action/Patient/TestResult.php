<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use App\Form\PatientTestPositiveType;
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
 * Class TestResult
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class TestResult extends BaseAction
{
    /**
     * Patch patient resource
     *
     * Update patient covid19 test result ( Access for Emergency Doctors only )
     *
     * @Rest\Patch("/api/v1/secured/patient-test-result/{guid}")
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
     *     @Model(type=PatientTestPositiveType::class)
     * )
     *
     * @SWG\Response(response=200, description="Patient resource patch success")
     * @SWG\Response(response=400, description="Validation Failed")
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
     * @return View|FormInterface
     */
    public function __invoke(Request $request, Patient $patient, PatientManager $pm, DoctorManager $dm)
    {
        try {
            $dm->canUpdatePatientTestResult($patient);
        } catch (Exception $e) {
            return $this->jsonResponse(
                Response::HTTP_FORBIDDEN,
                $e->getMessage()
            );
        }

        $form = $this->createForm(PatientTestPositiveType::class, $patient);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        $pm->update($patient);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Patient resource patch success'
        );
    }
}
