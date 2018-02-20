<?php

namespace JGI\IdentityNumberValidatorBundle\Tests\Validator\Constraints;

use byrokrat\id\OrganizationIdFactory;
use JGI\IdentityNumberValidatorBundle\Validator\Constraints\OrganizationNumber;
use JGI\IdentityNumberValidatorBundle\Validator\Constraints\OrganizationNumberValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use byrokrat\id\Exception;

class OrganizationNumberValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @return OrganizationNumberValidator
     */
    protected function createValidator()
    {
        return new OrganizationNumberValidator($this->createMock(OrganizationIdFactory::class));
    }

    /**
     * @test
     */
    public function organizationNumberIsValid()
    {
        $this->validator->validate('-', new OrganizationNumber());
        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function invalidOrganizationNumberIsInvalid()
    {
        $organizationIdFactoryMock = $this->createMock(OrganizationIdFactory::class);
        $organizationIdFactoryMock->expects($this->once())->method('createId')
            ->willThrowException(new class extends \Exception implements Exception {});
        $this->validator = new OrganizationNumberValidator($organizationIdFactoryMock);
        $this->validator->initialize($this->context);

        $this->validator->validate('-', new OrganizationNumber());
        $this
            ->buildViolation('This value is not a valid organization number.')
            ->assertRaised()
        ;
    }

    /**
     * @test
     */
    public function strictOrganizationNumberIsValid()
    {
        $this->validator = new OrganizationNumberValidator($this->createMock(OrganizationIdFactory::class));
        $this->validator->initialize($this->context);

        $this->validator->validate('5569209900', new OrganizationNumber(['strict' => true]));
        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function nonStrictOrganizationNumberIsInvalid()
    {
        $this->validator = new OrganizationNumberValidator($this->createMock(OrganizationIdFactory::class));
        $this->validator->initialize($this->context);

        $this->validator->validate('556920-9900', new OrganizationNumber(['strict' => true]));
        $this
            ->buildViolation('This value is not a valid organization number.')
            ->assertRaised()
        ;
    }
}
