<?php

namespace App\Entity\Traits;

/**
 * Trait ObjectMetaDataTrait

 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
trait ObjectMetaDataTrait
{
    /**
     * Getting object short class name
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getClass()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}