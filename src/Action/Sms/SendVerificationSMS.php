<?php

namespace App\Action\Sms;

use App\Action\BaseAction;
use App\Dto\Phone;
use App\Form\PhoneType;
use App\Service\TTSMSing;
use App\Util\Tools;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SendVerificationSMS
 *
 * @author Ghassen Karray <ghassen.karray@epfl.ch>
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class SendVerificationSMS extends BaseAction
{
    /**
     * Send verification sms to patient
     *
     * this endpoint is used to ensure 2 factors authentication for patient
     *
     * @Rest\Post("/api/v1/sms/authentication")
     *
     * @SWG\Parameter(
     *     name="phone",
     *     in="body",
     *     required=true,
     *     @Model(type=PhoneType::class)
     * )
     *
     * @SWG\Response(response=200, description="Verification SMS successfully sent to patient")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=500, description="Error with TT SMS Gateway")
     *
     * @SWG\Tag(name="SMS")
     *
     * @Rest\View()
     * @param Request $request
     * @param TTSMSing $ttSMSing
     * @return View|FormInterface
     */
    public function __invoke(Request $request, TTSMSing $ttSMSing)
    {
        $phone = new Phone();

        $form = $this->createForm(PhoneType::class, $phone);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        $verificationCode = Tools::generateRandomCode();

        try {
            $ttSMSing->send($phone->getNumber(), "Code de vérification : {$verificationCode}");
        } catch (Exception $exception) {
            return $this->jsonResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $exception->getMessage()
            );
        }

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Verification SMS successfully sent to patient',
            [
                'verificationCode' => $verificationCode
            ]
        );
    }
}
