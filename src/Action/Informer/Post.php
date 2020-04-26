<?php

namespace App\Action\Informer;

use App\Action\BaseAction;
use App\ApiEvents\GenericEvents;
use App\Entity\Informer;
use App\Entity\SMSVerification;
use App\Event\FileUploadEvent;
use App\Form\InformerType;
use App\Manager\InformerManager;
use App\Manager\SMSVerificationManager;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class Post
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Post extends BaseAction
{
    /**
     * Post Informer
     *
     * Save new Informer Resource
     *
     * @Rest\Post("/api/v1/informer")
     *
     * @SWG\Parameter(
     *     name="informer",
     *     in="body",
     *     required=true,
     *     @Model(type=InformerType::class)
     * )
     *
     * @SWG\Response(response=200, description="Informer resource add success")
     * @SWG\Response(response=400, description="Validation Failed")
     * @SWG\Response(response=403, description="Informer with phone number %phone number% can submit again in : %remaining time%")
     * @SWG\Response(response=404, description="Wrong sms verification pin code")
     *
     * @SWG\Tag(name="Informer")
     *
     * @Rest\View()
     * @param Request $request
     * @param InformerManager $im
     * @param EventDispatcherInterface $dispatcher
     * @param ParameterBagInterface $parameters
     * @param SMSVerificationManager $svm
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, InformerManager $im, EventDispatcherInterface $dispatcher, ParameterBagInterface $parameters, SMSVerificationManager $svm)
    {
        $informer = new Informer();

        $form = $this->createForm(InformerType::class, $informer);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        if ($timeToSubmitAgain = $im->canSubmit($informer)) {
            return $this->jsonResponse(
                Response::HTTP_FORBIDDEN,
                "Informer with phone number {$informer->getPhoneNumber()} can submit again in : {$timeToSubmitAgain}"
            );
        }

        // check two factor authentication with SMS
        $smsVerification = $svm->getLastSMSVerification($informer->getPhoneNumber(), SMSVerification::TYPE_INFORMER);

        if (!$smsVerification or $smsVerification->getPinCode() != $form->get('pinCode')->getData()) {
            return $this->jsonResponse(
                Response::HTTP_NOT_FOUND,
                "Wrong sms verification pin code"
            );
        }

        $im->save($informer);
        $svm->markAsUsed($smsVerification, $informer);

        // dispatch file upload event
        $dispatcher->dispatch(new FileUploadEvent(
            $informer,
            $form->get('image')->getData(),
            $parameters->get('upload_files_to_server') === 'true'
        ), GenericEvents::FILE_UPLOAD);
        

        return $this->jsonResponse(
            Response::HTTP_CREATED,
            'Informer resource add success'
        );
    }
}
