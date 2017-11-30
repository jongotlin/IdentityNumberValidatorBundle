# IdentityNumberValidatorBundle


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
 * @IdentityNumberAssert\OrganizationNumber
 */
private $organizationNumber;
```
