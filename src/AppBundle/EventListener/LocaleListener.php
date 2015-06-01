<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This class stores the logged in users locale within the current
 * session.
 *
 * The locale then can be used to make the locale sticky in a
 * request later on.
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 * @see http://symfony.com/doc/2.7/cookbook/session/locale_sticky_session.html
 */
class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;

    /**
     * set up the default locale
     *
     * @param string $defaultLocale Default site locale
     */
    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Set up the locale within the current request object.
     *
     * @param  GetResponseEvent $event Kernel response event
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}
