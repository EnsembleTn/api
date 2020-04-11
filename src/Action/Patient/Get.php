<?php

namespace App\Action\Patient;

use App\Action\BaseAction;
use App\Entity\Patient;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Get
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Get extends BaseAction
{
    /**
     * Get patient
     *
     * Get patient including questions responses
     *
     * @Rest\Get("/api/v1/secured/patient/{guid}")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     description="Api Authorization key",
     *     default="Bearer TOKEN"
     * )
     * @SWG\Response(response=200, description="Patient resource get success")
     * @SWG\Response(response=400, description="Missing Guid Parameter")
     * @SWG\Response(response=401, description="JWT Token not found / Invalid JWT Token / Identity has changed")
     * @SWG\Response(response=404, description="App\\Entity\\Patient object not found by the @ParamConverter annotation.")
     *
     * @SWG\Tag(name="Patient")
     *
     * @Rest\View(serializerGroups={"patient"})
     * @param Request $request
     * @param Patient $patient
     * @return View
     */
    public function __invoke(Request $request, Patient $patient)
    {
        return $this->jsonResponse(
            Response::HTTP_OK,
            'Patient resource get success',
            [
                'patient' => $patient
            ]
        );
    }
}
