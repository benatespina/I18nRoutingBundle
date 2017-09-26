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

use BenatEspina\I18nRoutingBundle\Resolver\EmptyNotFoundLocaleResolver;
use BenatEspina\I18nRoutingBundle\Resolver\NotFoundLocaleResolver;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class EmptyNotFoundLocaleResolverSpec extends ObjectBehavior
{
    function it_generates_url(Request $request)
    {
        $this->shouldHaveType(EmptyNotFoundLocaleResolver::class);
        $this->shouldImplement(NotFoundLocaleResolver::class);

        $request->getUri()->shouldBeCalled()->willReturn('http://benatespina.com/i18n-routing-bundle');
        $this->generateUrl($request, 'es', 'en')->shouldReturn('http://benatespina.com/i18n-routing-bundle');
    }

    function it_gets_locale(Request $request)
    {
        $this->shouldHaveType(EmptyNotFoundLocaleResolver::class);
        $this->shouldImplement(NotFoundLocaleResolver::class);

        $request->getLocale()->shouldBeCalled()->willReturn('es');
        $this->getLocale($request)->shouldReturn('es');
    }
}
