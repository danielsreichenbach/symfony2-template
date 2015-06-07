<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * Security context.
     *
     * @var AuthorizationCheckerInterface
     */
    protected $autorizationChecker;

    /**
     * Menu builder constructor
     *
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $securityContext
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $securityContext)
    {
        $this->factory             = $factory;
        $this->autorizationChecker = $securityContext;
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
            $menu->addChild('user.profile', array('route' => 'fos_user_profile_show'));
            $menu->addChild('user.logout', array('route' => 'fos_user_security_logout'));
        } else {
            $menu->addChild('user.login', array('route' => 'fos_user_security_login'));
            $menu->addChild('user.register', array('route' => 'fos_user_registration_register'));
        }

        return $menu;
    }
}
