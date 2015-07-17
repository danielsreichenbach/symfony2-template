<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

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
class UserLocaleListener
{
    /**
     * @var Session
     */
    private $session;

    /**
     * Set up the event listener
     *
     * @param Session $session The current User session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (null !== $user->getLocale()) {
            $this->session->set('_locale', $user->getLocale());
        }
    }
}
