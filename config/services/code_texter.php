<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umanit\TwoFactorSms\Texter\NotifierAuthCodeTexter;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('umanit_two_factor_sms.texter.auth_code_texter', NotifierAuthCodeTexter::class)
        ->args([
            service('texter')->ignoreOnInvalid(),
        ])
    ;
};
