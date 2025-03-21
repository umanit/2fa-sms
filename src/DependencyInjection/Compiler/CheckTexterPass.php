<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class CheckTexterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('umanit_two_factor_sms.security.sms_provider')) {
            return;
        }

        if (!$container->hasDefinition('texter')) {
            throw new LogicException(
                'Could not find texter service. Please install symfony/notifier and configure a texter_transports.'
            );
        }
    }
}
