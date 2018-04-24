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
use Enhavo\Bundle\NavigationBundle\Factory\NodeFactory;
use Symfony\Component\Form\FormFactoryInterface;

class ItemResolver implements ItemResolverInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getItemGroup($group)
    {

    }

    public function getItem($name)
    {
        $item = new Item();
        $item->setType('node');
        $item->setLabel('Node');
        $item->setTranslationDomain(null);
        return $item;
    }

    public function getItems()
    {
        return [
            $this->getItem('')
        ];
    }

    public function getFormType($item)
    {
        if($item == 'node') {
            return 'enhavo_navigation_node';
        }

        return null;
    }

    public function resolveFormBuilder($name)
    {
        $formType = $this->getFormType($name);
        $builder = $this->formFactory->createBuilder($formType);
        return $builder;
    }

    public function getFactory($type)
    {
        return new NodeFactory();
    }
}