<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

/**
 * Class AttachRefreshTokenOnSuccessListener
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class AttachRefreshTokenOnSuccessListener extends \Gesdinet\JWTRefreshTokenBundle\EventListener\AttachRefreshTokenOnSuccessListener
{
    public function attachRefreshToken(AuthenticationSuccessEvent $event)
    {
        parent::attachRefreshToken($event);

        $data['code'] = 200;
        $data['message'] = 'Refresh Token Success';
        $data['payload'] = $event->getData();

        $event->setData($data);
    }

}
