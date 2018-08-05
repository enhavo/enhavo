<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.08.18
 * Time: 17:57
 */

namespace Enhavo\Bundle\GridBundle\Resolver;

use Enhavo\Bundle\AppBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\AppBundle\Exception\ResolverException;
use Enhavo\Bundle\GridBundle\Factory\ItemFactory;
use Enhavo\Bundle\GridBundle\Factory\ItemTypeFactory;
use Enhavo\Bundle\GridBundle\Form\Type\ItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Enhavo\Bundle\GridBundle\Item\Item;
use Enhavo\Bundle\AppBundle\DynamicForm\ResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactoryInterface;

class ItemResolver implements ResolverInterface
{
    use ContainerAwareTrait;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ItemTypeFactory
     */
    private $itemTypeFactory;

    /**
     * @var Item[]
     */
    private $items = [];

    public function __construct(FormFactoryInterface $formFactory, TypeCollector $collector, ItemTypeFactory $itemTypeFactory, $configurations)
    {
        $this->formFactory = $formFactory;
        $this->itemTypeFactory = $itemTypeFactory;

        foreach($configurations as $name => $options) {
            /** @var AbstractConfiguration $configuration */
            $configuration = $collector->getType($options['type']);
            $item = new Item($configuration, $name, $options);
            $this->items[$name] = $item;
        }
    }

    public function resolveItemGroup($group)
    {
        return array_values($this->items);
    }

    /**
     * @param $name
     * @return Item
     * @throws \Exception
     */
    public function resolveItem($name)
    {
        if(!array_key_exists($name, $this->items)) {
            throw new ResolverException(sprintf('GridItem with name "%s" does not exist', $name));
        }

        return $this->items[$name];
    }

    public function resolveDefaultItems()
    {
        return array_values($this->items);
    }

    public function resolveForm($name, $data = null, $options = [])
    {
        $item = $this->resolveItem($name);

        $formOptions = [
            'item_type_form' => $item->getForm(),
            'item_type_parameters' => isset($options['item_type_parameters']) ?: [],
            'item_resolver' => 'enhavo_grid.resolver.item_resolver',
            'item_property' => 'name',
        ];

        $form = $this->formFactory->create(ItemType::class, $data, array_merge($formOptions, $options));
        return $form;
    }

    public function resolveFactory($name)
    {
        $item = $this->resolveItem($name);
        return new ItemFactory($this->itemTypeFactory, $name);
    }

    public function resolveFormTemplate($name)
    {
        $item = $this->resolveItem($name);
        return $item->getFormTemplate();
    }
}