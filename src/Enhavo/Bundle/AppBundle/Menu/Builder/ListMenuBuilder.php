<?php
/**
 * ListMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Builder;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuBuilder;
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
        foreach($this->menu as $name => $itemOptions) {
            /** @var MenuBuilderInterface $menuBuilder */
            $menuBuilder = $this->menuItemTypeCollector->getType($itemOptions['type']);
            $itemOptions['name'] = $name;
            if($menuBuilder->isGranted()) {
                $menu->addChild($menuBuilder->createMenu($itemOptions));
            }
        }
        return $menu;
    }

    public function getType()
    {
        return 'list';
    }
}