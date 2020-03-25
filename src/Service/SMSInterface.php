<?php

namespace App\Service;

/**
 * Interface SMSInterface
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
interface SMSInterface
{
    /**
     * Sending SMS
     *
     * @param int $destinationAddress
     * @param string $content
     * @return string
     */
    function send(int $destinationAddress, string $content): string ;
}
