# In memory strategy

This cookbook will try to show how is a basic in memory implementation of `ParametersResolver`.

Parameters resolver interface allows to centralize and decouple the route building from your storage strategy so, is
highly recommended.
```php
// src/AppBundle/Resolver/AwesomeParametersResolver.php

namespace AppBundle\Resolver;

use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolver;

class AwesomeParametersResolver implements ParametersResolver
{
    const IN_MEMORY_DB = [
        [
            'id'           => 1,
            'translations' => [
                [
                    'id'       => 1,
                    'title'    => 'Homepage',
                    'slug'     => '',
                    'locale'   => 'en',
                ],
                [
                    'id'       => 2,
                    'title'    => 'Inicio',
                    'slug'     => '',
                    'locale'   => 'es',
                ],
            ],
        ], [
            'id'           => 2,
            'translations' => [
                [
                    'id'       => 3,
                    'title'    => 'Contact',
                    'slug'     => 'contact',
                    'locale'   => 'en',
                ],
                [
                    'id'       => 4,
                    'title'    => 'Contacto',
                    'slug'     => 'contacto',
                    'locale'   => 'es',
                ],
            ],
        ],
    ];

    public function resolve($fromLocale, $toLocale, array &$parameters)
    {
        $slug = $parameters['slug'];
        
        foreach (self::IN_MEMORY_DB as $page) {
            if ($parameters['slug'] !== $slug) {
                break;
            }
            
            $translations = $page['translations']; // Aux trans to iterate with clean indexes

            foreach ($page['translations'] as $translation) {
                if ($translation['slug'] === $slug && $translation['locale'] === $fromLocale) {
                    foreach ($translations as $trans) {
                        if ($trans['locale'] === $toLocale) {
                            $parameters['slug'] = $trans['slug'];
                            break;
                        }
                    }
                    break;
                }
            }
        }
    }
}
```

- For more information about **Doctrine ORM** parameters resolver strategy check [this cookbook](usage_with_doctrine_orm.md).
- Back to the [index](index.md).
