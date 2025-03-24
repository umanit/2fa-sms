<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\DefaultTwoFactorFormRenderer;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Umanit\TwoFactorSms\DependencyInjection\Compiler\CheckTexterPass;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGeneratorInterface;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSenderInterface;
use Umanit\TwoFactorSms\Texter\AuthCodeTexterInterface;
use Umanit\TwoFactorSms\Texter\SmsMessageGeneratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class UmanitTwoFactorSmsBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition/*.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services/2fa_sms.php');

        if (!$config['enabled']) {
            $builder->removeDefinition('umanit_two_factor_sms.security.sms_provider');

            return;
        }

        $this->configureCodeGenerator($config, $container);
        $this->configureCodeSender($config, $container);
        $this->configureMessageGenerator($config, $container);
        $this->configureCodeTexter($config, $container);
        $this->configureSmsProvider($config, $container);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CheckTexterPass());
    }

    private function configureCodeGenerator(array $config, ContainerConfigurator $container): void
    {
        /** @var ?string $codeGenerator */
        $codeGenerator = $config['code_generator'];

        if (null === $codeGenerator) {
            $container->import('../config/services/code_generator.php');

            $codeGenerator = 'umanit_two_factor_sms.security.auth_code_generator';

            $container->services()->get($codeGenerator)->arg(0, $config['digits']);
        }

        $container->services()->alias(AuthCodeGeneratorInterface::class, $codeGenerator);
    }

    private function configureCodeSender(array $config, ContainerConfigurator $container): void
    {
        /** @var ?string $codeSender */
        $codeSender = $config['code_sender'];

        if (null === $codeSender) {
            $container->import('../config/services/code_sender.php');

            $codeSender = 'umanit_two_factor_sms.security.auth_code_sender';

            $defaultService = $container->services()->get($codeSender);
            $defaultService->arg(0, service(AuthCodeGeneratorInterface::class));
            $defaultService->arg(2, service(AuthCodeTexterInterface::class));
            $defaultService->arg(4, $config['expires_after']);
        }

        $container->services()->alias(AuthCodeSenderInterface::class, $codeSender);

        $container
            ->services()
            ->get('umanit_two_factor_sms.security.sms_provider')
            ->arg(0, service($codeSender))
        ;
    }

    private function configureMessageGenerator(array $config, ContainerConfigurator $container): void
    {
        /** @var ?string $messageGenerator */
        $messageGenerator = $config['message_generator'];

        if (null === $messageGenerator) {
            $container->import('../config/services/message_generator.php');

            $messageGenerator = 'umanit_two_factor_sms.texter.sms_message_generator';
            $container->services()->get($messageGenerator)->arg(0, $config['message']);
        }

        $container->services()->alias(SmsMessageGeneratorInterface::class, $messageGenerator);
    }

    private function configureCodeTexter(array $config, ContainerConfigurator $container): void
    {
        /** @var ?string $codeTexter */
        $codeTexter = $config['code_texter'];

        if (null === $codeTexter) {
            $container->import('../config/services/code_texter.php');

            $codeTexter = 'umanit_two_factor_sms.texter.auth_code_texter';
            $container->services()->get($codeTexter)->arg(1, service(SmsMessageGeneratorInterface::class));
        }

        $container->services()->alias(AuthCodeTexterInterface::class, $codeTexter);
    }

    private function configureSmsProvider(array $config, ContainerConfigurator $container): void
    {
        /** @var ?string $formRenderer */
        $formRenderer = $config['form_renderer'];

        if (null === $formRenderer) {
            $formRenderer = 'umanit_two_factor_sms.form_renderer';

            $container
                ->services()
                ->set('umanit_two_factor_sms.default_form_renderer', DefaultTwoFactorFormRenderer::class)
                ->lazy()
                ->args([
                    service('twig'),
                    $config['template'],
                ])
            ;
        }

        $container->services()->get('umanit_two_factor_sms.security.sms_provider')->arg(1, service($formRenderer));
    }
}
