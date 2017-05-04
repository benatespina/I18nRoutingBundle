<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenatEspina\I18nRoutingBundle\DependencyInjection\Compiler;

use BenatEspina\I18nRoutingBundle\DependencyInjection\Compiler\RegistryParametersResolversPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RegistryParametersResolversPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RegistryParametersResolversPass::class);
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_does_not_process_when_service_definition_does_not_exist(ContainerBuilder $container)
    {
        $container->hasDefinition(RegistryParametersResolversPass::SERVICE_ID)->shouldBeCalled()->willReturn(false);
        $this->process($container);
    }

    function it_does_not_process_when_service_does_not_have_any_alias(ContainerBuilder $container)
    {
        $container->hasDefinition(RegistryParametersResolversPass::SERVICE_ID)->shouldBeCalled()->willReturn(true);
        $container->getParameter('benat_espina_i18n_routing.config')->shouldBeCalled()->willReturn([
            'default_parameters_resolver' => '',
        ]);
        $container->findTaggedServiceIds('benat_espina_i18n_routing.parameters_resolver')
            ->shouldBeCalled()->willReturn([
                'app.resolver.my_parameters_resolver' => [
                    [
                        'other-attribute' => '',
                    ],
                ],
            ]);
        $this->shouldThrow(
            new \LogicException(
                'All the services tagged by "benat_espina_i18n_routing.parameters_resolver" must have "alias" property'
            )
        )->duringProcess($container);
    }

    function it_processes(ContainerBuilder $container, Definition $definition, Definition $definition2)
    {
        $container->hasDefinition(RegistryParametersResolversPass::SERVICE_ID)->shouldBeCalled()->willReturn(true);
        $container->getParameter('benat_espina_i18n_routing.config')->shouldBeCalled()->willReturn([
            'default_parameters_resolver' => 'my_app',
        ]);
        $container->findTaggedServiceIds('benat_espina_i18n_routing.parameters_resolver')
            ->shouldBeCalled()->willReturn([
                'app.resolver.my_parameters_resolver'       => [
                    [
                        'alias' => 'my_app',
                    ],
                ],
                'app.resolver.my_other_parameters_resolver' => [
                    [
                        'alias' => 'my_other_app',
                    ],
                ],
            ]);
        $container->getDefinition('app.resolver.my_parameters_resolver')->shouldBeCalled()->willReturn($definition);
        $definition2->replaceArgument(1, $definition)->shouldBeCalled()->willReturn($definition2);

        $container->getDefinition('app.resolver.my_other_parameters_resolver')->shouldBeCalled()->willReturn($definition);
        $container->findDefinition('benat_espina_i18n_routing.resolver.parameters_resolver_registry')
            ->shouldBeCalled()->willReturn($definition2);
        $definition2->replaceArgument(0, [$definition])->shouldBeCalled()->willReturn($definition2);
        $this->process($container);
    }
}
