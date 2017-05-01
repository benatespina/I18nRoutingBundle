<?php

/*
 * This file is part of the Language Selector Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenatEspina\LanguageSelectorBundle\Twig;

use BenatEspina\LanguageSelectorBundle\Resolver\ParametersResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TranslatablePathExtension extends \Twig_Extension
{
    private $requestStack;
    private $urlGenerator;
    private $parametersResolver;

    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        ParametersResolver $parametersResolver
    ) {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->parametersResolver = $parametersResolver;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('translatable_path', [$this, 'path']),
        ];
    }

    public function path($name, $parameters = [], $newLocale, $relative = false)
    {
        $locale = $this->requestStack->getMasterRequest()->getLocale();
        $parameters['_locale'] = $newLocale;

        $this->parametersResolver->resolve($locale, $newLocale, $parameters);

        return $this->urlGenerator->generate(
            $name,
            $parameters,
            $relative
                ? UrlGeneratorInterface::RELATIVE_PATH
                : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }
}
