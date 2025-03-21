<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGenerator;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGeneratorInterface;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSender;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSenderInterface;
use Umanit\TwoFactorSms\Security\SmsTwoFactorProvider;
use Umanit\TwoFactorSms\Texter\AuthCodeTexterInterface;
use Umanit\TwoFactorSms\Texter\NotifierAuthCodeTexter;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('umanit_two_factor_sms.security.code_generator', AuthCodeGenerator::class)
        ->args([
            abstract_arg('number digits'),
        ])
        ->alias(AuthCodeGeneratorInterface::class, 'umanit_two_factor_sms.security.code_generator')
    ;

    $services
        ->set('umanit_two_factor_sms.texter.auth_code_texter', NotifierAuthCodeTexter::class)
        ->args([service('texter')->ignoreOnInvalid()])
        ->alias(AuthCodeTexterInterface::class, 'umanit_two_factor_sms.texter.auth_code_texter')
    ;

    $services
        ->set('umanit_two_factor_sms.security.code_sender', AuthCodeSender::class)
        ->args([
            service('umanit_two_factor_sms.security.code_generator'),
            service('scheb_two_factor.persister'),
            service('umanit_two_factor_sms.texter.auth_code_texter'),
            service('clock'),
            abstract_arg('code expiry interval'),
        ])
        ->alias(AuthCodeSenderInterface::class, 'umanit_two_factor_sms.security.code_sender')
    ;

    $services
        ->set('umanit_two_factor_sms.security.sms_provider', SmsTwoFactorProvider::class)
        ->args([
            service('umanit_two_factor_sms.security.code_sender'),
            service('scheb_two_factor.security.form_renderer'),
            service('clock'),
        ])
        ->tag('scheb_two_factor.provider', ['alias' => 'sms'])
    ;
};
