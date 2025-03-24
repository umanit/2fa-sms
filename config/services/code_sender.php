<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSender;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('umanit_two_factor_sms.security.auth_code_sender', AuthCodeSender::class)
        ->args([
            abstract_arg('code generator'),
            service('scheb_two_factor.persister'),
            abstract_arg('code texter'),
            service('clock'),
            abstract_arg('code expiry interval'),
        ])
    ;
};
