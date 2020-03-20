<?php

namespace App\Action;

use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BaseAction
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class BaseAction extends AbstractController
{
    /**
     * Create Json Response
     *
     * @param int $status
     * @param string $message
     * @param mixed $payload
     * @param array $headers
     * @return View
     */
    protected function JsonResponse(int $status, string $message, $payload = null, array $headers = []): View
    {
        if (null !== $payload)
            return new View(['code' => $status, 'message' => $message, 'payload' => $payload], $status, $headers);
        else
            return new View(['code' => $status, 'message' => $message], $status);
    }
}
