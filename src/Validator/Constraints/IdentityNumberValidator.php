<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use byrokrat\id\CoordinationIdFactory;
use byrokrat\id\Exception;
use byrokrat\id\PersonalIdFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IdentityNumberValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PersonalIdFactory $personalIdFactory,
        private readonly CoordinationIdFactory $coordinationIdFactory
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IdentityNumber) {
            throw new UnexpectedTypeException($constraint, IdentityNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $message = $constraint->allowCoordinationNumber
            ? $constraint->messageWithCoordinationNumber
            : $constraint->message;

        if ($constraint->strict) {
            if (!preg_match("/^\d{12}$/", $value) ) {
                $this->context->addViolation($message);
                return;
            }
        }

        if ($constraint->allowCoordinationNumber) {
            try {
                $this->coordinationIdFactory->createId($value);

                return;
            } catch (\Exception $e) {}
        }

        try {
            $this->personalIdFactory->createId($value);
        } catch (\Exception $e) {
            $this->context->addViolation($message);
        }
    }
}
