<?php

namespace App\Action;

use App\Action\BaseAction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Action EntryPointController
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class EntryPoint extends BaseAction
{
    /**
     * Redirect to documentation if dev env
     *
     * @Route(name="entry_point", path="/")
     */
    public function __invoke()
    {
        if (in_array($this->getParameter('kernel.environment'), ['prod', 'test'])) {
            throw new NotFoundHttpException();
        }

        return $this->redirectToRoute('app.swagger_ui');
    }
}