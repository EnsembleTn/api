<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Manager\DoctorManager;
use App\Manager\PatientManager;
use App\Manager\QuestionManager;
use App\Service\TTSMSing;
use App\Util\Tools;
use GuzzleHttp\Exception\RequestException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Response as QuestionResponse;

/**
 * Class SendVerificationSMS
 *
 * @author Ghassen Karray <ghassen.karray@epfl.ch>
 */
class SendDoctorSMS extends BaseAction
{
    /**
     * Send SMS
     *
     * Send SMS to gateway
     *
     * @Rest\Post("/api/v1/secured/patient/send_doctor_sms/{guid}")
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
     * @SWG\Parameter(
     *     name="sms_content",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *      @SWG\Property(property="sms_content", type="string")
     * ),
     *     description="The SMS that will be sent to the patient"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="SMS sent")
     *
     * @SWG\Response(
     *     response=400,
     *     description="Validation Failed",
     *     @SWG\Schema(
     *      type="object",
     *      @SWG\Property(property="error", type="string")))
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View()
     * @param Request $request
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, Patient $patient,PatientManager $pm, TTSMSing $ttsms)
    {

        $number = $patient->getPhoneNumber();

        $content = $request->get('sms_content');

        try{
            $ttsms->send($number, $content);
        } catch (\Exception $exception) {
            return $this->jsonResponse(
                Response::HTTP_BAD_REQUEST,
                'Validation Failed',
                ['error' => $exception->getMessage()]
            );
        }

        $pm->updateSMS($patient, $content);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'SMS sent'
        );
    }
}
