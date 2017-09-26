<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\BenatEspina\I18nRoutingBundle;

use BenatEspina\I18nRoutingBundle\BenatEspinaI18nRoutingBundle;
use JMS\I18nRoutingBundle\JMSI18nRoutingBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebServerBundle\WebServerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Tests\BenatEspina\I18nRoutingBundle\Repository\InMemoryResourceRepository;
use Tests\BenatEspina\I18nRoutingBundle\Resolver\MyNotFoundLocaleResolver;
use Tests\BenatEspina\I18nRoutingBundle\Resolver\MyParametersResolver;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AppKernel extends Kernel
{
    const DEFAULT_LOCALE = 'en';
    const LOCALES = [self::DEFAULT_LOCALE, 'es'];
    const IN_MEMORY_DB = [
        [
            'id'           => 1,
            'translations' => [
                [
                    'id'       => 1,
                    'title'    => 'Homepage',
                    'slug'     => '',
                    'locale'   => 'en',
                    'template' => 'home',
                ],
                [
                    'id'       => 2,
                    'title'    => 'Inicio',
                    'slug'     => '',
                    'locale'   => 'es',
                    'template' => 'home',
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
                    'template' => 'contact',
                ],
                [
                    'id'       => 4,
                    'title'    => 'Contacto',
                    'slug'     => 'contacto',
                    'locale'   => 'es',
                    'template' => 'contact',
                ],
            ],
        ], [
            'id'           => 2,
            'translations' => [
                [
                    'id'       => 5,
                    'title'    => 'About Us',
                    'slug'     => 'about-us',
                    'locale'   => 'en',
                    'template' => 'about_us',
                ],
                [
                    'id'       => 6,
                    'title'    => 'Sobre Nosotros',
                    'slug'     => 'sobre-nosotros',
                    'locale'   => 'es',
                    'template' => 'about_us',
                ],
            ],
        ],
    ];

    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new BenatEspinaI18nRoutingBundle(),
            new FrameworkBundle(),
            new JMSI18nRoutingBundle(),
            new TwigBundle(),
            new WebServerBundle(),
        ];
    }

    public function getCacheDir()
    {
        return __DIR__ . '/../var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return __DIR__ . '/../var/logs';
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->setDefinition(
            'app.repository.in_memory_resource_repository',
            new Definition(
                InMemoryResourceRepository::class,
                [
                    self::IN_MEMORY_DB,
                ]
            )
        );
        $container->setDefinition(
            'app.resolver.my_parameters_resolver',
            new Definition(
                MyParametersResolver::class,
                [
                    $container->getDefinition(
                        'app.repository.in_memory_resource_repository'
                    ),
                ]
            )
        )->addTag('benat_espina_i18n_routing.parameters_resolver', ['alias' => 'my_app']);
        $container->setDefinition(
            'app.resolver.my_not_found_locale',
            new Definition(
                MyNotFoundLocaleResolver::class
            )
        )->addTag('benat_espina_i18n_routing.not_found_locale_resolver');

        $container->loadFromExtension('framework', [
            'secret'     => 'sd87cb6cb49c248cn3cnn439cn498ds0210sad2',
            'templating' => [
                'engines' => ['twig'],
            ],
            'translator' => [
                'fallbacks' => [self::DEFAULT_LOCALE],
                'paths'     => [
                    __DIR__ . '/../translations',
                ],
            ],
        ])->loadFromExtension('twig', [
            'paths' => [
                __DIR__ . '/../templates' => '__main__',
            ],
        ])->loadFromExtension('jms_i18n_routing', [
            'default_locale' => self::DEFAULT_LOCALE,
            'locales'        => self::LOCALES,
            'strategy'       => 'prefix_except_default',
        ]);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->add('/', 'kernel:pageAction', 'home');
        $routes->add('/{slug}/', 'kernel:pageAction', 'page');
    }

    public function pageAction(Request $request, $slug = '')
    {
        $repository = $this->getContainer()->get('app.repository.in_memory_resource_repository');
        $twig = $this->getContainer()->get('twig');

        $locale = $request->getLocale();
        $page = $repository->getTranslationBy($locale, ['slug' => $slug]);

        if (null === $page) {
            throw new NotFoundHttpException();
        }

        return new Response(
            $twig->render($page['template'] . '.html.twig', [
                'page' => $page,
            ])
        );
    }
}
