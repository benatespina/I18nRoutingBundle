<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenatEspina\I18nRoutingBundle\Twig;

use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolverDoesNotExist;
use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolverRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class CurrentPathTranslationExtension extends \Twig_Extension
{
    private $requestStack;
    private $urlGenerator;
    private $parametersResolverRegistry;

    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        ParametersResolverRegistry $parametersResolverRegistry
    ) {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->parametersResolverRegistry = $parametersResolverRegistry;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('current_path_translation', [$this, 'currentPathTranslation']),
        ];
    }

    public function currentPathTranslation($newLocale, $parametersResolverAlias = null, $relative = false)
    {
        $request = $this->requestStack->getMasterRequest();

        $locale = $request->getLocale();
        $name = $request->get('_route');
        $parameters = $request->get('_route_params');
        $parameters['_locale'] = $newLocale;

        $this->parametersResolver($parametersResolverAlias)->resolve($locale, $newLocale, $parameters);

        return $this->urlGenerator->generate(
            $name,
            $parameters,
            $relative
                ? UrlGeneratorInterface::RELATIVE_PATH
                : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    private function parametersResolver($alias = null)
    {
        if (null === $alias) {
            return $this->parametersResolverRegistry->getDefault();
        }
        if (!$this->parametersResolverRegistry->has($alias)) {
            throw new ParametersResolverDoesNotExist($alias);
        }

        return $this->parametersResolverRegistry->get($alias);
    }
}
