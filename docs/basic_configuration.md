# Prerequisites
### Translations
If you wish to use this bundle, you have to make sure you have translator enabled in your config.
```yml
# app/config/config.yml

framework:
    translator: { fallbacks: ["%locale%"] }
```

> For more information about translations, check
[Symfony documentation](https://symfony.com/doc/current/book/translation.html)

# Getting started
Once the bundle has been installed enable it in the AppKernel:

```php
// app/config/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...

        new BenatEspina\I18nRoutingBundle\BenatEspinaI18nRoutingBundle(),
    ];
}
```

After that, you need to implement the `BenatEspina\I18nRoutingBundle\Resolver\ParametersResolver` interface with your
desired strategy. Keep calm, in other chapters will try to explain this implementation purposes. ;)
The following snippet is the minimum code that bundle needs to work.
```php
// src/AppBundle/Resolver/AwesomeParametersResolver.php

namespace AppBundle\Resolver;

use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolver;

class AwesomeParametersResolver implements ParametersResolver
{
    public function resolve($fromLocale, $toLocale, array &$parameters)
    {
        // Do something...*
    }
}
```
>*You can check some information about this class in [here](usage_with_in_memory_strategy.md) or [here](usage_with_doctrine_orm.md).

Just created the resolver you need to register as service and tag it like this:
```yml
# app/config/services.yml

services:
    app.resolver.my_parameters_resolver:
        class: AppBundle\Resolver\AwesomeParametersResolver
        tags:
            -
                name: benat_espina_i18n_routing.parameters_resolver
                alias: awesome_resolver
```

With this basic configuration, now, you can use Twig's `current_path_translation` in an easy way:
```twig
{# app/Resources/views/your_favorite_template.html.twig #}

<ul class="language-selector">
    <li>
        <a href="{{ current_path_translation('en') }}">
            {{ 'languages.english'|trans }}
        </a>
    </li>
    <li>
        <a href="{{ current_path_translation('es') }}">
            {{ 'languages.spanish'|trans }}
        </a>
    </li>
</ul>
```

- For more information about **in memory** parameters resolver strategy check [this cookbook](usage_with_in_memory_strategy.md).
- For more information about **Doctrine ORM** parameters resolver strategy check [this cookbook](usage_with_doctrine_orm.md).
- Back to the [index](index.md).
