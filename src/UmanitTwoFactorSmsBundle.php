<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Umanit\TwoFactorSms\DependencyInjection\Compiler\CheckTexterPass;

class UmanitTwoFactorSmsBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition/*.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/*.php');

        if (!$config['enabled']) {
            $builder->removeDefinition('umanit_two_factor_sms.security.sms_provider');

            return;
        }

        $builder->getDefinition('umanit_two_factor_sms.security.code_generator')->setArgument(0, $config['digits']);
        $builder->getDefinition('umanit_two_factor_sms.security.code_sender')->setArgument(4, $config['expires_after']);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CheckTexterPass());
    }
}
