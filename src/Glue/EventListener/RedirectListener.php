<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class RedirectListener implements EventSubscriberInterface
{

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller_data = $event->getController();
        $controller_class = $controller_data[0];

        // redirect to a new location if the specified controller has queued up a redirect url
        if ($controller_class->getRedirectUrl()) {
            $event->setController(function() use ($controller_class) {
                    return new RedirectResponse($controller_class->getRedirectUrl());
                });
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }

}