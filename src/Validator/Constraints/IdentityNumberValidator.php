<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use byrokrat\id\CoordinationIdFactory;
use byrokrat\id\Exception;
use byrokrat\id\PersonalIdFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IdentityNumberValidator extends ConstraintValidator
{
    /**
     * @var PersonalIdFactory
     */
    protected $personalIdFactory;

    /**
     * @var CoordinationIdFactory
     */
    protected $coordinationIdFactory;

    /**
     * @param PersonalIdFactory $personalIdFactory
     * @param CoordinationIdFactory $coordinationIdFactory
     */
    public function __construct(PersonalIdFactory $personalIdFactory, CoordinationIdFactory $coordinationIdFactory)
    {
        $this->personalIdFactory = $personalIdFactory;
        $this->coordinationIdFactory = $coordinationIdFactory;
    }

    /**
     * @param mixed $value
     * @param Constraint|IdentityNumber $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
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
            } catch (Exception $e) {}
        }

        try {
            $this->personalIdFactory->createId($value);
        } catch (Exception $e) {
            $this->context->addViolation($message);
        }
    }
}
