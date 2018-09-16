<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\EventListener\LocaleListener;

class MyLocaleListener extends LocaleListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        parent::onKernelRequest($event);

        $request = $event->getRequest();
        $request->setLocale($request->getPreferredLanguage());
    }
}
