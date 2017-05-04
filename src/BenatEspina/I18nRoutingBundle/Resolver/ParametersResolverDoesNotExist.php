<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenatEspina\I18nRoutingBundle\Resolver;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ParametersResolverDoesNotExist extends \Exception
{
    public function __construct($alias)
    {
        parent::__construct(sprintf('Does not registered any parameters resolver with "%s" alias', $alias));
    }
}
