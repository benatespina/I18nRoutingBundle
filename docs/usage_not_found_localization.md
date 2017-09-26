# Not found localization

In order to customize the not found pages, this bundle offers an easy way to solve this problem.
The only thing that we need to do is create a class that implements
`BenatEspina\I18nRoutingBundle\Resolver\NotFoundLocaleResolver` interface with our custom project logic.

```php
// src/AppBundle/Resolver/MyNotFoundLocaleResolver.php

namespace AppBundle\Resolver;

use BenatEspina\I18nRoutingBundle\Resolver\NotFoundLocaleResolver;
use Symfony\Component\HttpFoundation\Request;

class AwesomeNotFoundLocaleResolver implements NotFoundLocaleResolver
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
```
After that, we need to register as a Symfony service tagging with `benat_espina_i18n_routing.not_found_locale_resolver`.
```yml
# app/config/services.yml

services:
    app.resolver.awesome_not_found_locale:
        class: AppBundle\Resolver\AwesomeNotFoundLocaleResolver
        tags:
            -
                name: benat_espina_i18n_routing.not_found_locale_resolver
```

- Back to the [index](index.md).
