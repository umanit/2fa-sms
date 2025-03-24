<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umanit\TwoFactorSms\Texter\SmsMessageGenerator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set('umanit_two_factor_sms.texter.sms_message_generator', SmsMessageGenerator::class)
        ->args([
            abstract_arg('message'),
        ])
    ;
};
