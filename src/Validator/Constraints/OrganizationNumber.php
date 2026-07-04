<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class OrganizationNumber extends Constraint
{
    public string $message = 'This value is not a valid organization number.';

    public bool $strict = false;

    public bool $allowPersonalIdNumber = false;

    public bool $allowCoordinationNumber = false;

    public function __construct(?string $message = null, ?bool $strict = null, ?bool $allowPersonalIdNumber = null, ?bool $allowCoordinationNumber = null)
    {
        $this->message = $message ?? $this->message;
        $this->strict = $strict ?? $this->strict;
        $this->allowPersonalIdNumber = $allowPersonalIdNumber ?? $this->allowPersonalIdNumber;
        $this->allowCoordinationNumber = $allowCoordinationNumber ?? $this->allowCoordinationNumber;
    }

    public function validatedBy(): string
    {
        return 'jgi.validator.organization_number';
    }
}
