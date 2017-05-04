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

use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolverDoesNotExist;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ParametersResolverDoesNotExistSpec extends ObjectBehavior
{
    function it_instantiates_exception()
    {
        $this->beConstructedWith('unknown-alias');
        $this->shouldHaveType(ParametersResolverDoesNotExist::class);
        $this->shouldHaveType(\Exception::class);
        $this->getMessage()->shouldReturn('Does not registered any parameters resolver with "unknown-alias" alias');
    }
}
