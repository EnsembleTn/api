<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use App\Form\PatientType;
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
 * Class Post
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Post extends BaseAction
{
    /**
     * @var PatientManager
     */
    private $pm;

    public function __construct(PatientManager $pm)
    {
        $this->pm = $pm;
    }

    /**
     * Post Patient
     *
     * Save new Patient Resource
     *
     * @Rest\Post("/api/v1/patient")
     *
     * @SWG\Parameter(
     *     name="patient",
     *     in="body",
     *     required=true,
     *     @Model(type=PatientType::class)
     * )
     *
     * @SWG\Response(response=200, description="Patient resource add success")
     * @SWG\Response(response=400, description="Validation Failed")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View()
     * @param Request $request
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        $patient = new Patient();

        $form = $this->createForm(PatientType::class, $patient);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }

        $this->pm->savePatient($patient);

        return $this->jsonResponse(
            Response::HTTP_CREATED,
            'Patient resource add success'
        );
    }
}
