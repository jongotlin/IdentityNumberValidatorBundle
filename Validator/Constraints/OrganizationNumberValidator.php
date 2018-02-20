<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use byrokrat\id\OrganizationIdFactory;
use byrokrat\id\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OrganizationNumberValidator extends ConstraintValidator
{
    /**
     * @var OrganizationIdFactory
     */
    protected $factory;

    /**
     * @param OrganizationIdFactory $factory
     */
    public function __construct(OrganizationIdFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param mixed $value
     * @param Constraint|OrganizationNumber $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($constraint->strict) {
            if (!preg_match("/^\d{10}$/", $value) ) {
                $this->context->addViolation($constraint->message);
                return;
            }
        }

        try {
            $this->factory->createId($value);
        } catch (Exception $e) {
            $this->context->addViolation($constraint->message);
        }
    }
}
