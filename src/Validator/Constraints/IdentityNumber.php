<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IdentityNumber extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is not a valid identity number.';

    /**
     * @var string
     */
    public $messageWithCoordinationNumber = 'This value is not a valid identity number or coordination number.';

    /**
     * @var bool
     */
    public $allowCoordinationNumber = false;

    /**
     * @var bool
     */
    public $strict = false;

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'jgi.validator.identity_number';
    }
}
