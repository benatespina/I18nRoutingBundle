<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenatEspina\I18nRoutingBundle\Resolver;

use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolver;
use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolverDoesNotExist;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ParametersResolverRegistrySpec extends ObjectBehavior
{
    function let(ParametersResolver $parametersResolver, ParametersResolver $defaultResolver)
    {
        $this->beConstructedWith(['my-app' => $parametersResolver], $defaultResolver);
    }

    function it_gets_default_when_does_not_exist_any_explicit_default_one(
        ParametersResolver $parametersResolver,
        ParametersResolver $parametersResolver2
    ) {
        $this->beConstructedWith(['my-app' => $parametersResolver, 'my-other-app' => $parametersResolver2]);
        $this->getDefault()->shouldReturn($parametersResolver);
    }

    function it_gets_default(ParametersResolver $defaultResolver)
    {
        $this->getDefault()->shouldReturn($defaultResolver);
    }

    function it_does_not_get_parameters_resolver_when_the_resolver_does_not_exist()
    {
        $this->shouldThrow(ParametersResolverDoesNotExist::class)->duringGet('unknown-app');
    }

    function it_gets_parameters_resolver(ParametersResolver $parametersResolver)
    {
        $this->get('my-app')->shouldReturn($parametersResolver);
    }

    function it_has_parameters_resolver()
    {
        $this->has('my-app')->shouldReturn(true);
        $this->has('unknown-app')->shouldReturn(false);
    }
}
