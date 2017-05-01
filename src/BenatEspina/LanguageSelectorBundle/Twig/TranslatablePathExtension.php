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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Tests\BenatEspina\LanguageSelectorBundle\AppKernel;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TranslatablePathExtension extends \Twig_Extension
{
    private $requestStack;
    private $urlGenerator;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $urlGenerator)
    {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
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
        $slug = isset($parameters['slug']) ? $parameters['slug'] : '';

        $page = $this->find($locale, $slug);
        if (null === $page) {
            throw new NotFoundHttpException();
        }

        if ($slug) {
            foreach ($page['translations'] as $translation) {
                if ($translation['locale'] === $newLocale) {
                    $parameters['slug'] = $translation['slug'];
                    break;
                }
            }
        }
        $parameters['_locale'] = $newLocale;

        return $this->urlGenerator->generate(
            $name,
            $parameters,
            $relative
                ? UrlGeneratorInterface::RELATIVE_PATH
                : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    private function find($locale, $slug)
    {
        $result = null;
        foreach (AppKernel::IN_MEMORY_DB as $el) {
            if (!isset($el['translations'])) {
                throw new \LogicException('Any translatable resource must have "translations"');
            }
            foreach ($el['translations'] as $translation) {
                if ($slug === $translation['slug'] && $locale === $translation['locale']) {
                    $result = $el;
                    break;
                }
            }
        }

        return $result;
    }
}
