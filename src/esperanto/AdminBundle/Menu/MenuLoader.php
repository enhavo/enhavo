<?php
/**
 * MenuCollection.php
 *
 * @since 08/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Menu;

use Knp\Menu\MenuFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MenuLoader
{
    /**
     * @var array
     */
    protected $menu;

    /**
     * @var MenuFactory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    public function __construct($name, $menu, MenuFactory $factory, EventDispatcherInterface $dispatcher)
    {
        $this->name = $name;
        $this->menu = $menu;
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
    }

    public function getMenu()
    {
        $menuList = $this->factory->createItem($this->name);

        foreach($this->menu as $name => $menu) {
            $menu = $this->factory->createItem($name, array(
                'route' => $menu['route']
            ));
            $menu->setLabel($menu['label']);
            $menuList->addChild($menu);
        }

        $event = new MenuEvent();
        $event->setMenu($menuList);
        $this->dispatcher->dispatch($this->name, $event);
        return $event->getMenu();
    }
}