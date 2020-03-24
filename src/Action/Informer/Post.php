<?php

namespace App\Action\Informer;

use App\Action\BaseAction;
use App\ApiEvents\GenericEvents;
use App\Entity\Informer;
use App\Event\FileUploadEvent;
use App\Form\InformerType;
use App\Manager\InformerManager;
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
     *
     * @SWG\Tag(name="Informer")
     *
     * @Rest\View()
     * @param Request $request
     * @param InformerManager $im
     * @param EventDispatcherInterface $dispatcher
     * @param ParameterBagInterface $parameters
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, InformerManager $im, EventDispatcherInterface $dispatcher, ParameterBagInterface $parameters)
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

        $im->save($informer);

        // dispatch file upload event
        $dispatcher->dispatch(new FileUploadEvent(
            $informer,
            $form->get('file')->getData(),
            $parameters->get('upload_files_to_server') === 'true' ? true : false
        ), GenericEvents::FILE_UPLOAD);
        

        return $this->jsonResponse(
            Response::HTTP_CREATED,
            'Informer resource add success'
        );
    }
}
