<?php

namespace JGI\IdentityNumberValidatorBundle\Validator\Constraints;

use byrokrat\id\CoordinationIdFactory;
use byrokrat\id\OrganizationIdFactory;
use byrokrat\id\Exception;
use byrokrat\id\PersonalIdFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OrganizationNumberValidator extends ConstraintValidator
{
    /**
     * @var OrganizationIdFactory
     */
    protected $organizationIdFactory;

    /**
     * @var PersonalIdFactory|null
     */
    protected $personalIdFactory;

    /**
     * @var CoordinationIdFactory|null
     */
    protected $coordinationIdFactory;

    /**
     * @param OrganizationIdFactory $factory
     */
    public function __construct(
        OrganizationIdFactory $organizationIdFactory,
        PersonalIdFactory $personalIdFactory = null,
        CoordinationIdFactory $coordinationIdFactory = null
    )
    {
        $this->organizationIdFactory = $organizationIdFactory;
        $this->personalIdFactory = $personalIdFactory;
        $this->coordinationIdFactory = $coordinationIdFactory;
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

        if ($constraint->allowPersonalIdNumber) {
            if (!$this->personalIdFactory) {
                throw new \LogicException('$personalIdFactory is not configured');
            }
            try {
                $this->personalIdFactory->createId($value);

                return;
            } catch (Exception $e) {}
        }

        if ($constraint->allowCoordinationNumber) {
            if (!$this->coordinationIdFactory) {
                throw new \LogicException('$coordinationIdFactory is not configured');
            }
            try {
                $this->coordinationIdFactory->createId($value);

                return;
            } catch (Exception $e) {}
        }

        try {
            $this->organizationIdFactory->createId($value);
        } catch (Exception $e) {
            $this->context->addViolation($constraint->message);
        }
    }
}
