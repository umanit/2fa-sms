# umanit/2fa-sms

[![Build Status](https://github.com/umanit/2fa-sms/actions/workflows/ci.yaml/badge.svg)](https://github.com/umanit/2fa-sms/actions?query=workflow%3ACI)
[![Latest Stable Version](https://img.shields.io/packagist/v/umanit/2fa-sms)](https://packagist.org/packages/umanit/2fa-sms)
[![License](https://poser.pugx.org/umanit/2fa-sms/license.svg)](https://packagist.org/packages/umanit/2fa-sms)

This package extends [scheb/2fa-bundle](https://github.com/scheb/2fa-bundle) with two-factor authentication using SMS.

## Installation

```
composer require umanit/2fa-sms
```

```php
// config/bundles.php
return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Scheb\TwoFactorBundle\SchebTwoFactorBundle::class => ['all' => true],
    Umanit\TwoFactorSms\UmanitTwoFactorSmsBundle::class => ['all' => true],
];
```

## Configuration

In order to work, the bundle uses Symfony's [Notifier](https://symfony.com/doc/current/notifier.html) component and the
SMS channel managed by the `texter` service.
You'll need to configure your application with
an [SMS channel](https://symfony.com/doc/current/notifier.html#sms-channel) before you can use this bundle.

These are the default configuration values.

```
# Default configuration for extension with alias: "umanit_two_factor_sms"
umanit_two_factor_sms:
    enabled:              true

    # A custom form renderer service that must implement "Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface".
    form_renderer:        null

    # Twig template to pass to "Scheb\TwoFactorBundle\Security\TwoFactor\Provider\DefaultTwoFactorFormRenderer".
    template:             '@SchebTwoFactor/Authentication/form.html.twig'

    # Custom auth code generator service that must implement "Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGeneratorInterface".
    code_generator:       null

    # The number of digits the generated auth code will have.
    digits:               6

    # Custom auth code sender service that must implement "Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSenderInterface".
    code_sender:          null

    # A valid \DateInterval used to expire the auth code.
    expires_after:        PT15M

    # Custom auth code texter service that must implement "Umanit\TwoFactorSms\Texter\AuthCodeTexterInterface".
    code_texter:          null

    # Custom texter message generator service that must implement "Umanit\TwoFactorSms\Texter\SmsMessageGeneratorInterface".
    message_generator:    null

    # Message send by the texter. The template string [[auth_code]] will be replaced with the actual auth code.
    message:              '[[auth_code]]'
```

## License

This software is available under the [MIT](LICENSE) license.
