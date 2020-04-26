<?php

namespace App\Action\Utils;

use App\Action\BaseAction;
use App\Manager\PatientManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Get
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Get extends BaseAction
{
    /**
     * This endpoint is used only for testing purpose
     *
     * This call revert all patient cases to ON_HOLD
     *
     * @Rest\Get("/api/v1/utils/revert-patient-cases")
     *
     * @SWG\Response(response=200, description="Patients cases reverted successfully")
     *
     * @SWG\Tag(name="Utils")
     *
     * @param Request $request
     * @param PatientManager $pm
     * @return View
     */
    public function __invoke(Request $request, PatientManager $pm)
    {
        if (in_array($this->getParameter('kernel.environment'), ['prod', 'test'])) {
            throw new NotFoundHttpException();
        }

        $pm->revertAll();

        return $this->jsonResponse(
            Response::HTTP_OK,
            'Patients cases reverted successfully'
        );
    }
}
