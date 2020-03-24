<?php

namespace App\Entity\Interfaces;

/**
 * Interface Uploadable
 *
 * Any uploadable entity should implement this interface
 * Uploaded file should be in encoded base64 format
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
interface Uploadable
{
    /**
     * Returns the entity upload path
     *
     * @return string
     */
    public function getUploadPath() : string ;
}
