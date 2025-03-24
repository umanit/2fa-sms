<?php

declare(strict_types=1);

namespace Symfony\Component\Config\Definition\Configurator;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\DefaultTwoFactorFormRenderer;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGeneratorInterface;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSenderInterface;
use Umanit\TwoFactorSms\Texter\AuthCodeTexterInterface;

return static function (DefinitionConfigurator $definition): void {
    $definition
        ->rootNode()
            ->canBeDisabled()
            ->children()
                ->scalarNode('form_renderer')
                    ->info(
                        \sprintf(
                            'A custom form renderer service that must implement "%s"',
                            TwoFactorFormRendererInterface::class
                        )
                    )
                    ->defaultNull()
                ->end()
                ->scalarNode('template')
                    ->info(\sprintf('Twig template to pass to "%s"', DefaultTwoFactorFormRenderer::class))
                    ->cannotBeEmpty()
                    ->defaultValue('@SchebTwoFactor/Authentication/form.html.twig')
                ->end()
                ->scalarNode('code_generator')
                    ->info(
                        \sprintf(
                            'Custom auth code generator service that must implement "%s"',
                            AuthCodeGeneratorInterface::class
                        )
                    )
                    ->defaultNull()
                ->end()
                ->integerNode('digits')
                    ->info('The number of digits the generated auth code will have.')
                    ->min(1)
                    ->defaultValue(6)
                ->end()
                ->scalarNode('code_sender')
                    ->info(
                        \sprintf(
                            'Custom auth code sender service that must implement "%s"',
                            AuthCodeSenderInterface::class
                        )
                    )
                    ->defaultNull()
                ->end()
                ->scalarNode('expires_after')
                    ->info('A valid \DateInterval used to expire the auth code.')
                    ->defaultValue('PT15M')
                    ->validate()
                        ->ifString()
                        ->then(static function (string $value): string {
                            try {
                                new \DateInterval($value);
                            } catch (\Throwable) {
                                throw new InvalidConfigurationException(
                                    '"umanit_two_factor_sms.expires_after" is not a valid \DateInterval value.'
                                );
                            }

                            return $value;
                        })
                    ->end()
                ->end()
                ->scalarNode('code_texter')
                    ->info(
                        \sprintf(
                            'Custom auth code texter service that must implement "%s"',
                            AuthCodeTexterInterface::class
                        )
                    )
                    ->defaultNull()
                ->end()
            ->end()
        ->end()
    ;
};
