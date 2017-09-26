# Doctrine ORM strategy

This cookbook will try to show how is a basic Doctrine ORM implementation of `ParametersResolver`. Suppose that we have
a multilingual (of course!) website which contains a CMS section to manage the page contents. For that purpose, we are
using a simple Doctrine ORM **Page** and **PageTranslation** entities like the following:
```php
// src/AppBundle/Entity/Page.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class Page
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PageTranslation", mappedBy="page")
     */
    private $translations;
    
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    // Here the getters and setters...
}



// src/AppBundle/Entity/PageTranslation.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="page_translation")
 */
class PageTranslation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $locale;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PageTranslation", inversedBy="translations")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;
    
    // Here the getters and setters...
}
```

The page and page translations are persisted inside database tables so, our implementation of parameters resolver
need to do something like this:
```php
// src/AppBundle/Resolver/AwesomeParametersResolver.php

namespace AppBundle\Resolver;

use AppBundle\Entity\PageTranslation;
use BenatEspina\I18nRoutingBundle\Resolver\ParametersResolver;
use Doctrine\ORM\EntityManager;

class AwesomeParametersResolver implements ParametersResolver
{
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function resolve($fromLocale, $toLocale, array &$parameters)
    {
        $slug = $parameters['slug'];
        
        $pageTranslation = $this->manager->getRepository(PageTranslation::class)->findOneBy([
            'locale' => $fromLocale,
            'slug'   => $slug
        ]);

        $page = $pageTranslation->getPage();
        $translation = $page->getTranslation($toLocale);
        if (!$translation) {
            throw new \Exception(sprintf(
                'Does not exist any translation to the given %s locale',
                $toLocale
            ));
        }
        
        $parameters['slug'] = $translation->getSlug();
    }
}
```

Obviously we need to update firstly declared service because in this case our resolver has collaborators.
```yml
# app/config/services.yml

services:
    app.resolver.my_parameters_resolver:
        class: AppBundle\Resolver\AwesomeParametersResolver
        arguments:
            - "@doctrine.orm.entity_manager"
        tags:
            -
                name: benat_espina_i18n_routing.parameters_resolver
                alias: awesome_resolver
```

- For more information about **in memory** parameters resolver strategy check [this cookbook](usage_with_in_memory_strategy.md).
- Back to the [index](index.md).
