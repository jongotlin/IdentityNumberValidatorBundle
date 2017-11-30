<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OrganizationNumber extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is not a valid organization number.';

    /**
     * @var bool
     */
    public $strict = false;
}
