<?php

namespace AppBundle\Service;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * The MenuBuilder is responsible for providing context sensitive menus for the site.
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Authorization checker
     *
     * @var AuthorizationCheckerInterface
     */
    protected $autorizationChecker;

    /**
     * Token storage
     *
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * Set up the menu builder service
     *
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $tokenstorage
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenstorage)
    {
        $this->factory             = $factory;
        $this->autorizationChecker = $authorizationChecker;
        $this->tokenStorage        = $tokenstorage;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createUserMenu(RequestStack $requestStack)
    {
        /**
         * KnpMenu item interface
         *
         * @var \Knp\Menu\ItemInterface
         */
        $menu = $this->factory->createItem(
            'root'
        );

        if ($this->autorizationChecker->isGranted('ROLE_USER')) {
            $user = $this->tokenStorage->getToken()->getUser();

            $menu->addChild('user.profile', array('route' => 'app_user_profile', 'routeParameters' => array('username' => $user->getUsername())));
            $menu->addChild('user.account_settings', array('route' => 'fos_user_profile_edit'));
            $menu->addChild('user.logout', array('route' => 'fos_user_security_logout'));
        } else {
            $menu->addChild('user.login', array('route' => 'fos_user_security_login'));
            $menu->addChild('user.register', array('route' => 'fos_user_registration_register'));
        }

        return $menu;
    }
}
