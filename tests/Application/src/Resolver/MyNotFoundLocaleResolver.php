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

use BenatEspina\I18nRoutingBundle\Resolver\NotFoundLocaleResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class MyNotFoundLocaleResolver implements NotFoundLocaleResolver
{
    public function generateUrl(Request $request, $locale, $newLocale)
    {
        if ($newLocale === 'en' && strstr($request->getPathInfo(), 'es')) {
            return strtr($request->getUri(), ['/es' => '']);
        } elseif ($newLocale === 'es' && !strstr($request->getPathInfo(), 'es')) {
            return '/es' . $request->getPathInfo();
        } elseif (($newLocale === 'es' && strstr($request->getPathInfo(), 'es'))
            || ($newLocale === 'en' && strstr($request->getPathInfo(), 'en'))
        ) {
            return $request->getPathInfo();
        }
    }

    public function getLocale(Request $request)
    {
        if (strstr($request->getPathInfo(), 'es')) {
            return 'es';
        }

        return 'en';
    }
}
