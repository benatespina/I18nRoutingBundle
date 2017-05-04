<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenatEspina\I18nRoutingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RegistryParametersResolversPass implements CompilerPassInterface
{
    const SERVICE_ID = 'benat_espina_i18n_routing.resolver.parameters_resolver_registry';

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::SERVICE_ID)) {
            return;
        }

        $config = $container->getParameter('benat_espina_i18n_routing.config');
        $services = $container->findTaggedServiceIds('benat_espina_i18n_routing.parameters_resolver');

        $parametersResolvers = [];
        foreach ($services as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['alias'])) {
                    throw new \LogicException(
                        'All the services tagged by "benat_espina_i18n_routing.parameters_resolver" ' .
                        'must have "alias" property'
                    );
                }
                if ($attributes['alias'] === $config['default_parameters_resolver']) {
                    $container->findDefinition(self::SERVICE_ID)->replaceArgument(
                        1,
                        $container->getDefinition($serviceId)
                    );
                    continue;
                }
                $parametersResolvers[] = $container->getDefinition($serviceId);
            }
        }
        $container->findDefinition(self::SERVICE_ID)->replaceArgument(
            0,
            $parametersResolvers
        );
    }
}
