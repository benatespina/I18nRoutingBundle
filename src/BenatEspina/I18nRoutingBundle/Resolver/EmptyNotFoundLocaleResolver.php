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

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class EmptyNotFoundLocaleResolver implements NotFoundLocaleResolver
{
    public function generateUrl(Request $request, $locale, $newLocale)
    {
        return $request->getUri();
    }

    public function getLocale(Request $request)
    {
        return $request->getLocale();
    }
}
