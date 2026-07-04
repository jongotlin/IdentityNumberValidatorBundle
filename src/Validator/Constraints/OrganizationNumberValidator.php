<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use byrokrat\id\CoordinationIdFactory;
use byrokrat\id\OrganizationIdFactory;
use byrokrat\id\PersonalIdFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OrganizationNumberValidator extends ConstraintValidator
{
    public function __construct(
        private readonly OrganizationIdFactory $organizationIdFactory,
        private readonly ?PersonalIdFactory $personalIdFactory = null,
        private readonly ?CoordinationIdFactory $coordinationIdFactory = null
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof OrganizationNumber) {
            throw new UnexpectedTypeException($constraint, OrganizationNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($constraint->strict) {
            if (!preg_match("/^\d{10}$/", $value)) {
                $this->context->addViolation($constraint->message);

                return;
            }
        }

        if ($constraint->allowPersonalIdNumber) {
            if (!$this->personalIdFactory) {
                throw new \LogicException('$personalIdFactory is not configured');
            }
            try {
                $this->personalIdFactory->createId($value);

                return;
            } catch (\Exception $e) {
            }
        }

        if ($constraint->allowCoordinationNumber) {
            if (!$this->coordinationIdFactory) {
                throw new \LogicException('$coordinationIdFactory is not configured');
            }
            try {
                $this->coordinationIdFactory->createId($value);

                return;
            } catch (\Exception $e) {
            }
        }

        try {
            $this->organizationIdFactory->createId($value);
        } catch (\Exception $e) {
            $this->context->addViolation($constraint->message);
        }
    }
}
