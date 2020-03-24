<?php

namespace App\Validator\constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class FileMimeTypeConstraintValidator
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class FileMimeTypeConstraintValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof FileMimeTypeConstraint) {
            throw new UnexpectedTypeException($constraint, FileMimeTypeConstraint::class);
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

        $mimeType = finfo_buffer(finfo_open(), base64_decode($value), FILEINFO_MIME_TYPE);

        if (!in_array($mimeType, $constraint->mimeTypes)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{types}}', implode(',', $constraint->mimeTypes))
                ->addViolation();
        }
    }
}
