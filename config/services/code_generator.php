<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGenerator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('umanit_two_factor_sms.security.auth_code_generator', AuthCodeGenerator::class)
        ->args([
            abstract_arg('number digits'),
        ])
    ;
};
