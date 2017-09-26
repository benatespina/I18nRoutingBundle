<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenatEspina\I18nRoutingBundle\EventListener;

use BenatEspina\I18nRoutingBundle\EventListener\NotFoundLocaleListener;
use BenatEspina\I18nRoutingBundle\Resolver\NotFoundLocaleResolver;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class NotFoundLocaleListenerSpec extends ObjectBehavior
{
    function it_listens_on_set_locale(
        NotFoundLocaleResolver $resolver,
        GetResponseForExceptionEvent $event,
        NotFoundHttpException $notFoundHttpException,
        Request $request
    ) {
        $this->beConstructedWith($resolver);
        $this->shouldHaveType(NotFoundLocaleListener::class);

        $event->isMasterRequest()->shouldBeCalled()->willReturn(true);
        $event->getException()->shouldBeCalled()->willReturn($notFoundHttpException);
        $event->getRequest()->shouldBeCalled()->willReturn($request);

        $resolver->getLocale($request)->shouldBeCalled()->willReturn('es');

        $request->setLocale('es')->shouldBeCalled();

        $this->onSetLocale($event);
    }

    function it_does_not_listen_when_the_request_is_no_master(
        NotFoundLocaleResolver $resolver,
        GetResponseForExceptionEvent $event
    ) {
        $this->beConstructedWith($resolver);
        $this->shouldHaveType(NotFoundLocaleListener::class);

        $event->isMasterRequest()->shouldBeCalled()->willReturn(false);

        $this->onSetLocale($event);
    }

    function it_does_not_listen_when_the_exception_is_not_not_found_http_exception(
        NotFoundLocaleResolver $resolver,
        GetResponseForExceptionEvent $event,
        \Exception $otherException
    ) {
        $this->beConstructedWith($resolver);
        $this->shouldHaveType(NotFoundLocaleListener::class);

        $event->isMasterRequest()->shouldBeCalled()->willReturn(true);
        $event->getException()->shouldBeCalled()->willReturn($otherException);

        $this->onSetLocale($event);
    }
}
