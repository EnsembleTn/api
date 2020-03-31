<?php

namespace App\Action\Sms;

use App\Action\BaseAction;
use App\Dto\Phone;
use App\Dto\VerificationCodeDto;
use App\Form\PhoneType;
use App\Form\VerificationCodeType;
use App\Manager\SMSManager;
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
 * Class VerifySMS
 *
 * @author Ghassen Karray <ghassen.karray@epfl.ch>
 */
class VerifySMS extends BaseAction
{
    /**
     * Check if verification sms entered by the patient is valid
     *
     *
     * @Rest\Post("/api/v1/sms/authentication/check")
     *
     * @SWG\Parameter(
     *     name="verificationCode",
     *     in="body",
     *     required=true,
     *     @Model(type=VerificationCodeType::class)
     * )
     *
     * @SWG\Response(response=200, description="Verification code check success")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=500, description="No SMS found for this phone number")
     *
     * @SWG\Tag(name="SMS")
     *
     * @Rest\View()
     * @param Request $request
     * @param SMSManager $sm
     * @return View|FormInterface
     */
    public function __invoke(Request $request, SMSManager $sm)
    {
        $verificationCode = new VerificationCodeDto();

        $form = $this->createForm(VerificationCodeType::class, $verificationCode);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        try{
            $sms = $sm->getByPhoneNumber($verificationCode->getNumber());
        } catch (Exception $exception) {
            return $this->jsonResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $exception->getMessage()
            );
        }

        if($sms->getVerificationCode() != $verificationCode->getCode()) {
            return $this->jsonResponse(
                Response::HTTP_BAD_REQUEST,
                "Validation Failed"
            );
        }

        $sm->remove($sms);

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Verification code check success'
        );
    }
}
