<?php

namespace App\Validator\constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class FileMimeTypeConstraint
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class FileMimeTypeConstraint extends Constraint
{
    public $message = 'the file type must be one of following types [{{types}}]';
    public $mimeTypes = [];

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->mimeTypes = $options['mimeTypes'];
    }

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}

