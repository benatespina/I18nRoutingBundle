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
class SetNotFoundLocaleResolverPass implements CompilerPassInterface
{
    const TWIG_EXTENSION_ID = 'benat_espina_i18n_routing.twig.current_path_translation_extension';
    const LISTENER_ID = 'benat_espina_i18n_routing.event_listener.not_found_locale';

    public function process(ContainerBuilder $container)
    {
        $this->loadDependency($container, self::LISTENER_ID, 0);
        $this->loadDependency($container, self::TWIG_EXTENSION_ID, 3);
    }

    private function loadDependency(ContainerBuilder $container, $serviceId, $position)
    {
        if (!$container->hasDefinition($serviceId)) {
            return;
        }
        $services = $container->findTaggedServiceIds('benat_espina_i18n_routing.not_found_locale_resolver');

        if (count($services) > 1) {
            throw new \LogicException(
                'Only one service can have the "benat_espina_i18n_routing.not_found_locale_resolver" tag.'
            );
        }

        $container->findDefinition($serviceId)->replaceArgument($position, $container->getDefinition(key($services)));
    }
}
