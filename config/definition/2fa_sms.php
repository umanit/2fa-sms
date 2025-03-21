<?php

declare(strict_types=1);

namespace Symfony\Component\Config\Definition\Configurator;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

return static function (DefinitionConfigurator $definition): void {
    $definition
        ->rootNode()
            ->canBeDisabled()
            ->children()
                ->integerNode('digits')
                    ->info('The number of digits the generated auth code will have.')
                    ->min(1)
                    ->defaultValue(6)
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
            ->end()
        ->end()
    ;
};
