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

use BenatEspina\I18nRoutingBundle\DependencyInjection\Compiler\SetNotFoundLocaleResolverPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SetNotFoundLocaleResolverPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SetNotFoundLocaleResolverPass::class);
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_does_not_process_when_service_definition_does_not_exist(ContainerBuilder $container)
    {
        $container->hasDefinition(SetNotFoundLocaleResolverPass::LISTENER_ID)
            ->shouldBeCalled()->willReturn(false);
        $container->hasDefinition(SetNotFoundLocaleResolverPass::TWIG_EXTENSION_ID)
            ->shouldBeCalled()->willReturn(false);
        $this->process($container);
    }

    function it_does_not_process_when_there_are_multiple_tagged_services(ContainerBuilder $container)
    {
        $container->hasDefinition(SetNotFoundLocaleResolverPass::LISTENER_ID)->shouldBeCalled()->willReturn(true);
        $container->findTaggedServiceIds('benat_espina_i18n_routing.not_found_locale_resolver')
            ->shouldBeCalled()->willReturn([
                'app.resolver.my_not_found_locale_resolver'  => [[]],
                'app.resolver.my_not_found_locale_resolver2' => [[]],
            ]);
        $this->shouldThrow(
            new \LogicException(
                'Only one service can have the "benat_espina_i18n_routing.not_found_locale_resolver" tag.'
            )
        )->duringProcess($container);
    }

    function it_processes(
        ContainerBuilder $container,
        Definition $definition,
        Definition $definition2,
        Definition $definition3
    ) {
        $container->hasDefinition(SetNotFoundLocaleResolverPass::LISTENER_ID)->shouldBeCalled()->willReturn(true);
        $container->findTaggedServiceIds('benat_espina_i18n_routing.not_found_locale_resolver')
            ->shouldBeCalled()->willReturn(['app.resolver.my_not_found_locale_resolver' => [[]]]);

        $container->hasDefinition(SetNotFoundLocaleResolverPass::TWIG_EXTENSION_ID)->shouldBeCalled()->willReturn(true);
        $container->findTaggedServiceIds('benat_espina_i18n_routing.not_found_locale_resolver')
            ->shouldBeCalled()->willReturn(['app.resolver.my_not_found_locale_resolver' => [[]]]);

        $container->getDefinition('app.resolver.my_not_found_locale_resolver')->shouldBeCalled()->willReturn($definition);
        $container->findDefinition(SetNotFoundLocaleResolverPass::LISTENER_ID)->shouldBeCalled()->willReturn($definition2);
        $definition2->replaceArgument(0, $definition)->shouldBeCalled()->willReturn($definition2);

        $container->getDefinition('app.resolver.my_not_found_locale_resolver')->shouldBeCalled()->willReturn($definition);
        $container->findDefinition(SetNotFoundLocaleResolverPass::TWIG_EXTENSION_ID)->shouldBeCalled()->willReturn($definition3);
        $definition3->replaceArgument(3, $definition)->shouldBeCalled()->willReturn($definition3);

        $this->process($container);
    }
}
