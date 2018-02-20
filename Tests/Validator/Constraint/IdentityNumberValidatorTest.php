<?php

namespace JGI\IdentityNumberValidatorBundle\Tests\Validator\Constraints;

use byrokrat\id\CoordinationIdFactory;
use byrokrat\id\PersonalIdFactory;
use JGI\IdentityNumberValidatorBundle\Validator\Constraints\IdentityNumber;
use JGI\IdentityNumberValidatorBundle\Validator\Constraints\IdentityNumberValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use byrokrat\id\Exception;

class IdentityNumberValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @return IdentityNumberValidator
     */
    protected function createValidator()
    {
        return new IdentityNumberValidator(
            $this->createMock(PersonalIdFactory::class),
            $this->createMock(CoordinationIdFactory::class)
        );
    }

    /**
     * @test
     */
    public function socialSecurityNumberIsValid()
    {
        $this->validator->validate('-', new IdentityNumber());
        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function invalidSocialSecurityNumberIsInvalid()
    {
        $personalIdFactoryMock = $this->createMock(PersonalIdFactory::class);
        $personalIdFactoryMock->expects($this->once())->method('createId')
            ->willThrowException(new class extends \Exception implements Exception {});
        $this->validator = new IdentityNumberValidator(
            $personalIdFactoryMock,
            $this->createMock(CoordinationIdFactory::class)
        );
        $this->validator->initialize($this->context);

        $this->validator->validate('-', new IdentityNumber());
        $this
            ->buildViolation('This value is not a valid identity number.')
            ->assertRaised()
        ;
    }

    /**
     * @test
     */
    public function invalidCoordinationNumberIsInvalid()
    {
        $personalIdFactoryMock = $this->createMock(PersonalIdFactory::class);
        $personalIdFactoryMock->expects($this->once())->method('createId')
            ->willThrowException(new class extends \Exception implements Exception {});
        $coordinationIdFactoryMock = $this->createMock(CoordinationIdFactory::class);
        $coordinationIdFactoryMock->expects($this->once())->method('createId')
            ->willThrowException(new class extends \Exception implements Exception {});
        $this->validator = new IdentityNumberValidator(
            $personalIdFactoryMock,
            $coordinationIdFactoryMock
        );
        $this->validator->initialize($this->context);

        $this->validator->validate('-', new IdentityNumber(['allowCoordinationNumber' => true]));
        $this
            ->buildViolation('This value is not a valid identity number or coordination number.')
            ->assertRaised()
        ;
    }

    /**
     * @test
     */
    public function coordinationNumberIsValid()
    {
        $this->validator->validate('-', new IdentityNumber(['allowCoordinationNumber' => true]));
        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function coordinationNumberWithoutAllowConfigIsInvalid()
    {
        $personalIdFactoryMock = $this->createMock(PersonalIdFactory::class);
        $personalIdFactoryMock->expects($this->once())->method('createId')
            ->willThrowException(new class extends \Exception implements Exception {});
        $coordinationIdFactoryMock = $this->createMock(CoordinationIdFactory::class);
        $coordinationIdFactoryMock->expects($this->never())->method('createId');
        $this->validator = new IdentityNumberValidator(
            $personalIdFactoryMock,
            $coordinationIdFactoryMock
        );
        $this->validator->initialize($this->context);

        $this->validator->validate('-', new IdentityNumber());
        $this
            ->buildViolation('This value is not a valid identity number.')
            ->assertRaised()
        ;
    }

    /**
     * @test
     */
    public function strictSocialSecurityNumberIsValid()
    {
        $this->validator = new IdentityNumberValidator(
            $this->createMock(PersonalIdFactory::class),
            $this->createMock(CoordinationIdFactory::class)
        );
        $this->validator->initialize($this->context);

        $this->validator->validate('195605158616', new IdentityNumber(['strict' => true]));
        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function nonStrictSocialSecurityNumberIsInvalid()
    {
        $this->validator = new IdentityNumberValidator(
            $this->createMock(PersonalIdFactory::class),
            $this->createMock(CoordinationIdFactory::class)
        );
        $this->validator->initialize($this->context);

        $this->validator->validate('560515-8616', new IdentityNumber(['strict' => true]));
        $this
            ->buildViolation('This value is not a valid identity number.')
            ->assertRaised()
        ;
    }
}
