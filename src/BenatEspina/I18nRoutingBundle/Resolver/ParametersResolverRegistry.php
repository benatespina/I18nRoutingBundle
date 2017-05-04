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
class ParametersResolverRegistry
{
    private $parametersResolvers;
    private $defaultResolver;

    public function __construct(array $parametersResolvers, ParametersResolver $defaultResolver = null)
    {
        $this->parametersResolvers = $parametersResolvers;
        $this->defaultResolver = $defaultResolver;
    }

    public function getDefault()
    {
        return null === $this->defaultResolver ? array_values($this->parametersResolvers)[0] : $this->defaultResolver;
    }

    public function get($alias)
    {
        if (!$this->has($alias)) {
            throw new ParametersResolverDoesNotExist($alias);
        }

        return $this->parametersResolvers[$alias];
    }

    public function has($alias)
    {
        return array_key_exists($alias, $this->parametersResolvers);
    }
}
