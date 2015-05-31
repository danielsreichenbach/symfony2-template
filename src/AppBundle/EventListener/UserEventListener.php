<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

/**
 * This class allows you to hook into FOSUserBundle controllers as defined in the
 * documentation.
 *
 * Common use cases for this are:
 *
 *   * triggering special handling when a user changes his password, and you require
 *     access to the plaintext password.
 *   * triggering a reaction when a user confirms his account, e.g. by sending a
 *     welcome message, or assigning a user to a default group with standard roles
 *     on completing the registration.
 *
 * See https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/controller_events.md
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserEventListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Injecting the requirements
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirmed',
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess',
        );
    }

    /**
     * React to a successful registration prior to encrypting the password
     *
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $event->getForm()->getData();

        // ... do something which requires access to the unencrypted password
    }

    /**
     * React to a confirmed registration
     *
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationConfirmed(FilterUserResponseEvent $event)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $event->getUser();

        // ... do something after a user is confirmed, e.g send a welcome message
    }

    /**
     * React to a password update prior to encrypting the password
     *
     * @param FilterUserResponseEvent $event
     */
    public function onChangePasswordSuccess(FormEvent $event)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $event->getForm()->getData();

        // ... do something which requires access to the unencrypted password
    }

    /**
     * React to a password reset prior to encrypting the password
     *
     * @param FilterUserResponseEvent $event
     */
    public function onResettingResetSuccess(FormEvent $event)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $event->getForm()->getData();

        // ... do something which requires access to the unencrypted password
    }
}
