<?php

namespace App\Validator\constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Base64StringConstraint
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Base64StringConstraint extends Constraint
{
    public $message = 'The provided base64 encoded string is invalid';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}

