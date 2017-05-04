<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenatEspina\I18nRoutingBundle;

use BenatEspina\I18nRoutingBundle\DependencyInjection\Compiler\RegistryParametersResolversPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenatEspinaI18nRoutingBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegistryParametersResolversPass());
    }
}
