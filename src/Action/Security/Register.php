<?php

namespace App\Action\Security;

use App\Action\BaseAction;
use App\ApiEvents\DoctorEvents;
use App\Entity\Doctor;
use App\Event\DoctorEvent;
use App\Form\RegistrationType;
use App\Manager\DoctorManager;
use Exception;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class Register
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Register extends BaseAction
{
    /**
     * Doctor Registration
     *
     * Create doctor account
     *
     * @Rest\Post("/api/v1/security/register")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @Model(type=RegistrationType::class)
     * )
     *
     * @SWG\Response(response=201,description="Account successfully created")
     * @SWG\Response(response=400, description="Validation Failed")
     *
     * @SWG\Tag(name="Security")
     *
     * @Rest\View()
     * @param Request $request
     * @param DoctorManager $dm
     * @param EventDispatcherInterface $dispatcher
     * @return View|FormInterface
     * @throws Exception
     */
    public function __invoke(Request $request, DoctorManager $dm, EventDispatcherInterface $dispatcher)
    {
        $doctor = new Doctor();
        $form = $this->createForm(RegistrationType::class, $doctor);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $dm->registerDoctor($doctor);

        $dispatcher->dispatch(new DoctorEvent($doctor), DoctorEvents::DOCTOR_REGISTRATION_SUCCESS);

        return $this->JsonResponse(
            Response::HTTP_CREATED,
            'Account successfully created'
        );
    }
}