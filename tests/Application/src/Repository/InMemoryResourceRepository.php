<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\BenatEspina\I18nRoutingBundle\Repository;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InMemoryResourceRepository implements ResourceRepository
{
    private $resources;

    public function __construct(array $resources = [])
    {
        $this->resources = $resources;
    }

    public function getTranslatableBy($locale, array $criteria)
    {
        $criteriaKey = key($criteria);

        foreach ($this->resources as $resource) {
            $this->checkTranslationsKeyExists($resource);

            foreach ($resource['translations'] as $translation) {
                $this->checkLocaleKeyExists($translation);

                if ($criteria[$criteriaKey] === $translation[$criteriaKey]
                    && $locale === $translation['locale']
                ) {
                    return $resource;
                }
            }
        }
        throw new ResourceDoesNotExistException();
    }

    public function getTranslationBy($locale, array $criteria)
    {
        $criteriaKey = key($criteria);

        foreach ($this->resources as $resource) {
            $this->checkTranslationsKeyExists($resource);

            foreach ($resource['translations'] as $translation) {
                $this->checkLocaleKeyExists($translation);

                if ($criteria[$criteriaKey] === $translation[$criteriaKey]
                    && $locale === $translation['locale']
                ) {
                    return $translation;
                }
            }
        }
        throw new ResourceDoesNotExistException();
    }

    private function checkTranslationsKeyExists($resource)
    {
        if (!isset($resource['translations'])) {
            throw new \LogicException('Any translatable resource must have "translations"');
        }
    }

    private function checkLocaleKeyExists($resource)
    {
        if (!isset($resource['locale'])) {
            throw new \LogicException('Any translation resource must have "locale"');
        }
    }
}
