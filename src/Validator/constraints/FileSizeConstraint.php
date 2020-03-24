<?php

namespace App\Validator\constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class FileSizeConstraint
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class FileSizeConstraint extends Constraint
{
    public $message = 'the file size must not exceed {{size}} ko';
    public $size = 1024;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (isset($options['size']))
            $this->size = $options['size'];
    }

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}

