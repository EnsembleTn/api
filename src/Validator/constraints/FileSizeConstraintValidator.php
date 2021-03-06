<?php

namespace App\Validator\constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class FileSizeConstraintValidator
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class FileSizeConstraintValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof FileSizeConstraint) {
            throw new UnexpectedTypeException($constraint, FileSizeConstraint::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        $size = strlen(base64_decode($value));

        if ($size > $constraint->size) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{size}}', $constraint->size)
                ->addViolation();
        }
    }
}
