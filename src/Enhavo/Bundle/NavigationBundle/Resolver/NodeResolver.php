<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 14:10
 */

namespace Enhavo\Bundle\NavigationBundle\Resolver;

use Enhavo\Bundle\AppBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\AppBundle\DynamicForm\ItemInterface;
use Enhavo\Bundle\AppBundle\Exception\ResolverException;
use Enhavo\Bundle\NavigationBundle\Factory\NodeFactory;
use Enhavo\Bundle\NavigationBundle\Item\Item;
use Enhavo\Bundle\AppBundle\DynamicForm\ResolverInterface;
use Enhavo\Bundle\NavigationBundle\Item\ItemManager;
use Sylius\Component\Resource\Factory\FactoryInterface as SyliusFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormFactoryInterface;

class NodeResolver implements ResolverInterface
{
    use ContainerAwareTrait;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Item[]
     */
    private $items = [];

    public function __construct(FormFactoryInterface $formFactory, ItemManager $itemManager)
    {
        $this->formFactory = $formFactory;
        $this->items = $itemManager->getItems();
    }

    /**
     * @param string[] $groups
     * @return ItemInterface[]
     */
    public function resolveItemGroup($groups = [])
    {
        $items = [];
        foreach($this->items as $item) {
            foreach($groups as $group) {
                if(in_array($group, $item->getGroups())) {
                    if(!in_array($item, $items)) {
                        $items[] = $item;
                    }
                }
            }
        }
        return $items;
    }

    /**
     * @param $name
     * @return Item
     * @throws \Exception
     */
    public function resolveItem($name)
    {
        if(!array_key_exists($name, $this->items)) {
            throw new \Exception(sprintf('Navigation with name "%s" does not exist', $name));
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
            'item_resolver' => 'enhavo_navigation.resolver.node_resolver',
            'data_class' => $item->getModel(),
            'configuration_type' => $item->getConfigurationForm(),
            'configuration_type_options' => [],
            'content_type' => $item->getContentForm(),
            'content_type_options' => [],
        ];
        $form = $this->formFactory->create($item->getForm(), $data, array_merge($formOptions, $options));
        return $form;
    }

    public function resolveFactory($name)
    {
        $item = $this->resolveItem($name);

        /** @var NodeFactory $factory */
        $factory = $this->getFactory($item);
        $factory->setContentFactory($this->getContentFactory($item));
        $factory->setConfigurationFactory($this->getConfigurationFactory($item));
        return $factory;
    }

    /**
     * @param Item $item
     * @throws ResolverException
     * @return FactoryInterface
     */
    private function getContentFactory(Item $item)
    {
        $contentFactoryClass = $item->getContentFactory();
        if($contentFactoryClass) {
            if ($this->container->has($contentFactoryClass)) {
                $contentFactory = $this->container->get($contentFactoryClass);
            } else {
                $contentFactory = new $contentFactoryClass($item->getContentModel());
            }

            if(!($contentFactory instanceof FactoryInterface || $contentFactory instanceof SyliusFactoryInterface)) {
                throw new ResolverException(sprintf('Factory class "%s" not type of "%s" or "%s', get_class($contentFactory),FactoryInterface::class, SyliusFactoryInterface::class));
            }

            return $contentFactory;
        }
        return null;
    }

    /**
     * @param Item $item
     * @throws ResolverException
     * @return FactoryInterface
     */
    private function getConfigurationFactory(Item $item)
    {
        $configurationFactoryClass = $item->getConfigurationFactory();
        if($configurationFactoryClass) {
            if ($this->container->has($configurationFactoryClass)) {
                $configurationFactory = $this->container->get($configurationFactoryClass);
            } else {
                $configurationFactory = new $configurationFactoryClass();
            }

            if(!$configurationFactory instanceof FactoryInterface) {
                throw new ResolverException(sprintf('Factory class "%s" not type of "%s"', get_class($configurationFactory), FactoryInterface::class));
            }

            return $configurationFactory;
        }
        return null;
    }

    /**
     * @param Item $item
     * @return FactoryInterface
     * @throws ResolverException
     */
    private function getFactory(Item $item)
    {
        $factoryClass = $item->getFactory();
        if($factoryClass) {
            if ($this->container->has($factoryClass)) {
                $factory = $this->container->get($factoryClass);
            } else {
                $factory = new $factoryClass($item->getModel(), $item->getName());
            }

            if(!$factory instanceof FactoryInterface) {
                throw new ResolverException(sprintf('Factory class "%s" not type of "%s"', get_class($factory), FactoryInterface::class));
            }

            return $factory;
        }
        throw new ResolverException(sprintf('Factory for type "%s" navigation is required', $item->getName()));
    }

    public function resolveFormTemplate($name)
    {
        $item = $this->resolveItem($name);
        return $item->getTemplate();
    }
}