<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IdentityNumber extends Constraint
{
    public string $message = 'This value is not a valid identity number.';

    public string $messageWithCoordinationNumber = 'This value is not a valid identity number or coordination number.';

    public bool $allowCoordinationNumber = false;

    public bool $strict = false;

    public function __construct(?string $message = null, ?string $messageWithCoordinationNumber = null, ?bool $allowCoordinationNumber = null, ?bool $strict = null)
    {
        $this->message = $message ?? $this->message;
        $this->messageWithCoordinationNumber = $messageWithCoordinationNumber ?? $this->messageWithCoordinationNumber;
        $this->allowCoordinationNumber = $allowCoordinationNumber ?? $this->allowCoordinationNumber;
        $this->strict = $strict ?? $this->strict;
    }

    public function validatedBy(): string
    {
        return 'jgi.validator.identity_number';
    }
}
