<?php
/**
 * MenuEventListener.php
 *
 * @since 08/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AdminBundle\EventListener;

use Enhavo\Bundle\AdminBundle\Menu\MenuEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuEventListener
{
    /**
     * @var boolean
     */
    protected $permissionCheck;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var array
     */
    protected $menu;

    /**
     * @var RequestStack
     */
    protected $stack;

    public function __construct($permissionCheck, AuthorizationCheckerInterface $authorizationChecker, FactoryInterface $factory, $menu, RequestStack $stack)
    {
        $this->permissionCheck = $permissionCheck;
        $this->authorizationChecker = $authorizationChecker;
        $this->factory = $factory;
        $this->menu = $menu;
        $this->stack = $stack;
    }

    public function onMenu(MenuEvent $event)
    {
        $menu = $event->getMenu();
        foreach($menu->getChildren() as $child) {
            /** @var $child ItemInterface */
            if($this->permissionCheck) {
                $role = $this->menu[$child->getName()]['role'];
                if(!$this->authorizationChecker->isGranted($role)) {
                    $child->setDisplay(false);
                }
            }
            $this->addClass($child);
            $this->addCurrent($child);
        }
        $this->addLogout($menu);
    }

    public function addClass(ItemInterface $menu)
    {
        $menu->setAttribute('class', sprintf('menu-icon-%s', $menu->getName()));
    }

    public function addCurrent(ItemInterface $menu)
    {
        $currentRoute = $this->stack->getMasterRequest()->get('_route');
        $route = $this->menu[$menu->getName()]['route'];
        if($currentRoute == $route) {
            $menu->setCurrent(true);
        }
    }

    public function addLogout(ItemInterface $menu)
    {
        $logout = $this->factory->createItem('logout', array(
            'route' => 'fos_user_security_logout'
        ));
        $logout->setLabel('label.logout');
        $logout->setAttribute('class', 'menu-icon-logout');
        $menu->addChild($logout);
    }
}