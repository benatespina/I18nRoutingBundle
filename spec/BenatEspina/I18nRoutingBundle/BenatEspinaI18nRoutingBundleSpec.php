<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenatEspina\I18nRoutingBundle;

use BenatEspina\I18nRoutingBundle\BenatEspinaI18nRoutingBundle;
use BenatEspina\I18nRoutingBundle\DependencyInjection\Compiler\RegistryParametersResolversPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenatEspinaI18nRoutingBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BenatEspinaI18nRoutingBundle::class);
        $this->shouldHaveType(Bundle::class);
    }

    function it_builds(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegistryParametersResolversPass())->shouldBeCalled();
        $this->build($container);
    }
}
