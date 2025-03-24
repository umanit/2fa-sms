<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umanit\TwoFactorSms\Security\SmsTwoFactorProvider;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('umanit_two_factor_sms.security.sms_provider', SmsTwoFactorProvider::class)
        ->args([
            abstract_arg('code sender'),
            abstract_arg('form renderer'),
            service('clock'),
        ])
        ->tag('scheb_two_factor.provider', ['alias' => 'sms'])
    ;
};
