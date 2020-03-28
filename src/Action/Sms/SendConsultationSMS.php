<?php

namespace App\Action\Sms;

use App\Action\BaseAction;
use App\Dto\Sms;
use App\Entity\Patient;
use App\Form\SmsType;
use App\Manager\DoctorManager;
use App\Manager\PatientManager;
use App\Service\TTSMSing;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SendConsultationSMS
 *
 * @author Ghassen Karray <ghassen.karray@epfl.ch>
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class SendConsultationSMS extends BaseAction
{
    /**
     * Send consultation to patient
     *
     * This endpoint is called before submitting patient data to check first if consultation sms is sent successfully or not to patient with {guid}
     *
     * @Rest\Post("/api/v1/secured/sms/consultation/{guid}")
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
     *     name="sms",
     *     in="body",
     *     required=true,
     *     @Model(type=SmsType::class)
     * )
     *
     * @SWG\Response(response=200, description="Consultation SMS successfully sent to patient")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=403, description="Forbidden")
     * @SWG\Response(response=500, description="Error with TT SMS Gateway")
     *
     * @SWG\Tag(name="SMS")
     *
     * @Rest\View()
     * @param Request $request
     * @param Patient $patient
     * @param PatientManager $pm
     * @param DoctorManager $dm
     * @param TTSMSing $ttSMSing
     * @return View|FormInterface
     */
    public function __invoke(Request $request, Patient $patient, PatientManager $pm, DoctorManager $dm, TTSMSing $ttSMSing)
    {

        try {
            $dm->canSendSms($patient);
        } catch (Exception $e) {
            return $this->jsonResponse(
                Response::HTTP_FORBIDDEN,
                $e->getMessage()
            );
        }

        $number = $patient->getPhoneNumber();

        $sms = new Sms();

        $form = $this->createForm(SmsType::class, $sms);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        try {
            $ttSMSing->send($number, $sms->getContent());
        } catch (Exception $exception) {
            return $this->jsonResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $exception->getMessage()
            );
        }

        // update SMSs fields
        if ($dm->getCurrentDoctor()->isEmergencyDoctor())
            $patient->setEmergencySms($sms->getContent());
        else
            $patient->setDoctorSms($sms->getContent());

        $pm->update($patient);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Consultation SMS successfully sent to patient'
        );
    }
}
