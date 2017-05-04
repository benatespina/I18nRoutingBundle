<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenatEspina\I18nRoutingBundle\DependencyInjection;

use BenatEspina\I18nRoutingBundle\DependencyInjection\BenatEspinaI18nRoutingExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenatEspinaI18nRoutingExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BenatEspinaI18nRoutingExtension::class);
        $this->shouldHaveType(Extension::class);
    }

    function it_loads(ContainerBuilder $container)
    {
        $this->load([], $container);
    }
}
