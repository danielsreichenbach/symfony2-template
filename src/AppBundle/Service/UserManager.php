<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class provides access to common user functionality and thus allows easier
 * reuse.
 *
 * Common use cases for this are:
 *
 *   * special User events
 *   * retrieving or updating specific User collections
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Injecting the requirements
     *
     * @param EntityManager      $entityManager
     * @param ContainerInterface $containerInterface
     */
    public function __construct(EntityManager $entityManager, ContainerInterface $containerInterface)
    {
        $this->entityManager = $entityManager;
        $this->container     = $containerInterface;
    }

    /**
     * Adds the given user to the sites default group.
     *
     * @param User $user
     */
    public function addUserTodefaultGroup(User $user)
    {
        $defaultGroup = $this->container->getParameter('app.users.default_group');

        /** @var \AppBundle\Repository\GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository('AppBundle:Group');
        /** @var \AppBundle\Entity\Group $group */
        $group = $groupRepository->findOneBy(array('name' => $defaultGroup));
        $user->addGroup($group);
        $this->entityManager->flush();
    }

    /**
     * Send a welcome mail to a user completing the registration process.
     *
     * @param User $user
     */
    public function onRegistrationConfirmed(User $user)
    {
        $mailerAddress = $this->container->getParameter('app.mail.address');
        $mailerSenderName = $this->container->getParameter('app.mail.sender_name');
        $templating = $this->container->get('templating');

        $renderedTemplate = $templating->render('messages/users/welcome.txt.twig', array('user' => $user));

        $this->sendEmailMessage($renderedTemplate, array($mailerAddress => $mailerSenderName), array($user->getEmailCanonical() => $user->getUsernameCanonical()));
    }

    /**
     * Send a notification to a user when the password was updated.
     *
     * @param User $user
     */
    public function onChangePasswordSuccess(User $user)
    {
        $mailerAddress = $this->container->getParameter('app.mail.address');
        $mailerSenderName = $this->container->getParameter('app.mail.sender_name');
        $templating = $this->container->get('templating');

        $renderedTemplate = $templating->render('messages/users/change_password.txt.twig', array('user' => $user));

        $this->sendEmailMessage($renderedTemplate, array($mailerAddress => $mailerSenderName), array($user->getEmailCanonical() => $user->getUsernameCanonical()));
    }

    /**
     * Send a notification to a user when the password was reset.
     *
     * @param User $user
     */
    public function onResettingResetSuccess(User $user)
    {
        $mailerAddress = $this->container->getParameter('app.mail.address');
        $mailerSenderName = $this->container->getParameter('app.mail.sender_name');
        $templating = $this->container->get('templating');

        $renderedTemplate = $templating->render('messages/users/resetting.txt.twig', array('user' => $user));

        $this->sendEmailMessage($renderedTemplate, array($mailerAddress => $mailerSenderName), array($user->getEmailCanonical() => $user->getUsernameCanonical()));
    }

    /**
     * Send a rendered email to the recipient
     *
     * @param string $renderedTemplate
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        $mailer = $this->container->get('mailer');

        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = $mailer->createMessage()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);

        $mailer->send($message);
    }
}
