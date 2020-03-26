<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use App\Form\PatientType;
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
class SendVerificationSMS extends BaseAction
{
    /**
     * Send SMS
     *
     * Send SMS to gateway
     *
     * @Rest\Post("/api/v1/patient/send_verification_sms")
     *
     * @SWG\Parameter(
     *     name="phone_number",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *      @SWG\Property(property="phone_number", type="integer")
     * ),
     *     description="The phonenumber that will receive validation code"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="SMS sent",
     *     @SWG\Schema(
     *       @SWG\Property(property="verification_number", type="integer")))
     *
     * @SWG\Response(
     *     response=400,
     *     description="Validation Failed",
     *     @SWG\Schema(
     *      @SWG\Property(property="error", type="string")))
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View()
     * @param Request $request
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, TTSMSing $ttsms)
    {
        $number = $request->get("phone_number");

        $random_number = Tools::generateRandomCode();

        try{
            $ttsms->send($number, $random_number);
        } catch (\Exception $exception) {
            return $this->jsonResponse(
                Response::HTTP_BAD_REQUEST,
                'Validation Failed',
                ['error' => $exception->getMessage()]
            );
        }

//        return $response;
        return $this->jsonResponse(
            Response::HTTP_OK,
            'SMS sent',
            ['verification_number' => $random_number]
        );
    }
}
