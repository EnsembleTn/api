<?php

namespace App\Validator\constraints;

use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Validator\Constraint;

/**
 * Class CollectionSameItem
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class CollectionSameItem extends Constraint
{
    const ERROR_PATH_FORM = 'form';
    const ERROR_PATH_FIELD = 'field';

    public $message = 'Item already exists in collection "%collectionName%" .'; // %variable%
    public $variable;               // property access for specific variable
    public $toString;               // property access of item
    public $collection;             // property access for collection
    public $errorPath = self::ERROR_PATH_FIELD;    // "form" or "field" or "form,field"

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->collection) {
            throw new MissingOptionsException(sprintf('Option collection" must be given for constraint %s', __CLASS__));
        }

        if (!$this->toString) {
            $this->toString = $this->variable;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
