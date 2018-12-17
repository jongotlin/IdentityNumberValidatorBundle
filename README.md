# IdentityNumberValidatorBundle

[![Build Status](https://img.shields.io/travis/jongotlin/IdentityNumberValidatorBundle/master.svg)](https://travis-ci.org/jongotlin/IdentityNumberValidatorBundle)

This Symfony Bundle uses [github.com/byrokrat/id](https://github.com/byrokrat/id) to validate swedish identity number (personnummer), coordination number (samordningsnummer) and organization number (organisationsnummer).

## Install

Via Composer

```bash
$ composer require jongotlin/identity-number-bundle
```

```php
class AppKernel extends Kernel
{
  public function registerBundles()
  {
    $bundles = array(
        // ...
        new JGI\IdentityNumberValidatorBundle\IdentityNumberValidatorBundle(),
    }
  }
}
```

## Usage
```php
use JGI\IdentityNumberValidatorBundle\Validator\Constraints as IdentityNumberAssert;

/**
 * @IdentityNumberAssert\IdentityNumber(allowCoordinationNumber=true)
 */
private $identityNumber;

/**
 * @IdentityNumberAssert\OrganizationNumber(allowPersonalIdNumber=true, allowCoordinationNumber=true)
 */
private $organizationNumber;
```

Available options are
 - `allowCoordinationNumber` When set to true coordination number (samordningsnummer) is accepted. Default is false.
 - `allowPersonalIdNumber` When set to true personal identity number is accepted as organization number. Default is false.
 - `strict` When set to true identity number must be exactly 12 digits and organization number 10 digits. No hyphen is accepted. If false identity number can be either 10 or 12 digits and with or without hyphen (or plus sign). Default is false.
