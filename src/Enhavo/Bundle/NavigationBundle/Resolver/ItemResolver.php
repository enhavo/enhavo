<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 14:10
 */

namespace Enhavo\Bundle\NavigationBundle\Resolver;

use Enhavo\Bundle\AppBundle\DynamicForm\Item;
use Enhavo\Bundle\AppBundle\DynamicForm\ItemResolverInterface;

class ItemResolver implements ItemResolverInterface
{
    public function getItemGroup($group)
    {

    }

    public function getItem($name)
    {

    }

    public function getItems()
    {
        $item = new Item();
        $item->setType('node');
        $item->setLabel('Node');
        $item->setTranslationDomain(null);

        return [
            $item
        ];
    }

    public function getFormType($item)
    {
        if($item == 'node') {
            return 'enhavo_navigation_node';
        }

        return null;
    }
}