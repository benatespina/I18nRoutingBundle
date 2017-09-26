<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenatEspina\I18nRoutingBundle\EventListener;

use BenatEspina\I18nRoutingBundle\Resolver\NotFoundLocaleResolver;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class NotFoundLocaleListener
{
    private $resolver;

    public function __construct(NotFoundLocaleResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function onSetLocale(GetResponseForExceptionEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($event->getException() instanceof NotFoundHttpException) {
            $event->getRequest()->setLocale(
                $this->resolver->getLocale($event->getRequest())
            );
        }
    }
}
