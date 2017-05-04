<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenatEspina\I18nRoutingBundle\Twig;

use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolver;
use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolverDoesNotExist;
use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolverRegistry;
use BenatEspina\I18nRoutingBundle\Twig\CurrentPathTranslationExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class CurrentPathTranslationExtensionSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack, UrlGeneratorInterface $urlGenerator, ParametersResolverRegistry $registry)
    {
        $this->beConstructedWith($requestStack, $urlGenerator, $registry);
    }

    function it_gets_functions()
    {
        $this->shouldHaveType(CurrentPathTranslationExtension::class);
        $this->shouldHaveType(\Twig_Extension::class);

        $this->getFunctions()->shouldBeArray();
    }

    function it_builds_current_path_translation(
        RequestStack $requestStack,
        Request $request,
        ParametersResolverRegistry $registry,
        ParametersResolver $resolver,
        UrlGeneratorInterface $urlGenerator
    ) {
        $parameters = ['slug' => 'the-slug', '_locale' => 'en'];

        $requestStack->getMasterRequest()->shouldBeCalled()->willReturn($request);
        $request->getLocale()->shouldBeCalled()->willReturn('es');
        $request->get('_route')->shouldBeCalled()->willReturn('page_route');
        $request->get('_route_params')->shouldBeCalled()->willReturn(['slug' => 'the-slug']);
        $registry->getDefault()->shouldBeCalled()->willReturn($resolver);
        $resolver->resolve('es', 'en', $parameters)->shouldBeCalled();
        $urlGenerator->generate('page_route', $parameters, 1)->shouldBeCalled()->willReturn('/the-slug');
        $this->currentPathTranslation('en');
    }

    function it_does_no_build_current_path_translation_when_parameters_resolver_does_not_exist(
        RequestStack $requestStack,
        Request $request,
        ParametersResolverRegistry $registry
    ) {
        $requestStack->getMasterRequest()->shouldBeCalled()->willReturn($request);
        $request->getLocale()->shouldBeCalled()->willReturn('es');
        $request->get('_route')->shouldBeCalled()->willReturn('page_route');
        $request->get('_route_params')->shouldBeCalled()->willReturn(['slug' => 'the-slug']);
        $registry->has('unknown-alias')->shouldBeCalled()->willReturn(false);
        $this->shouldThrow(ParametersResolverDoesNotExist::class)->duringCurrentPathTranslation('en', 'unknown-alias');
    }

    function it_builds_current_path_translation_with_explicit_alias(
        RequestStack $requestStack,
        Request $request,
        ParametersResolverRegistry $registry,
        ParametersResolver $resolver,
        UrlGeneratorInterface $urlGenerator
    ) {
        $parameters = ['slug' => 'the-slug', '_locale' => 'en'];

        $requestStack->getMasterRequest()->shouldBeCalled()->willReturn($request);
        $request->getLocale()->shouldBeCalled()->willReturn('es');
        $request->get('_route')->shouldBeCalled()->willReturn('page_route');
        $request->get('_route_params')->shouldBeCalled()->willReturn(['slug' => 'the-slug']);
        $registry->has('explicit-alias')->shouldBeCalled()->willReturn(true);
        $registry->get('explicit-alias')->shouldBeCalled()->willReturn($resolver);
        $resolver->resolve('es', 'en', $parameters)->shouldBeCalled();
        $urlGenerator->generate('page_route', $parameters, 1)->shouldBeCalled()->willReturn('/the-slug');
        $this->currentPathTranslation('en', 'explicit-alias');
    }
}
