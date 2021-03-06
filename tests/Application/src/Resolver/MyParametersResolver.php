<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\BenatEspina\I18nRoutingBundle\Resolver;

use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolver;
use Tests\BenatEspina\I18nRoutingBundle\Repository\ResourceRepository;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class MyParametersResolver implements ParametersResolver
{
    private $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function resolve($fromLocale, $toLocale, array &$parameters)
    {
        $slug = isset($parameters['slug']) ? $parameters['slug'] : '';

        if ($slug) {
            $resource = $this->repository->getTranslatableBy($fromLocale, ['slug' => $slug]);

            foreach ($resource['translations'] as $translation) {
                if ($translation['locale'] === $toLocale) {
                    $parameters['slug'] = $translation['slug'];
                    break;
                }
            }
        }
    }
}
