<?php
/**
 * ListMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Builder;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuBuilder;
use Enhavo\Bundle\AppBundle\Menu\MenuItemInterface;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\AppBundle\Menu\MenuBuilderInterface;

class ListMenuBuilder extends AbstractMenuBuilder
{
    /**
     * @var TypeCollector
     */
    protected $menuItemTypeCollector;

    /**
     * @var array
     */
    protected $menu;

    /**
     * ListMenuBuilder constructor.
     *
     * @param TypeCollector $menuItemTypeCollector
     * @param $menu
     */
    public function __construct(TypeCollector $menuItemTypeCollector, $menu)
    {
        $this->menuItemTypeCollector = $menuItemTypeCollector;
        $this->menu = $menu;
    }

    public function createMenu(array $options)
    {
        parent::createMenu($options);
        $name = 'list';
        if(isset($options['name'])) {
            $name = $options['name'];
        }

        $menu = $this->getFactory()->createItem($name);
        if(is_array($this->menu)) {
            foreach($this->menu as $name => $options) {
                /** @var MenuBuilderInterface $menuBuilder */
                $menuBuilder = $this->menuItemTypeCollector->getType($options['type']);
                $menuItem  = $menuBuilder->createMenu($options);
                $options['name'] = $name;
                if($menuBuilder->isGranted($menuItem)) {
                    $menu->addChild($menuBuilder->createMenu($options));
                }
            }
        }
        return $menu;
    }

    public function getType()
    {
        return 'list';
    }
}