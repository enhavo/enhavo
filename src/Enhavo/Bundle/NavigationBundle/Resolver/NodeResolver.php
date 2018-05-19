<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 14:10
 */

namespace Enhavo\Bundle\NavigationBundle\Resolver;

use Enhavo\Bundle\AppBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\NavigationBundle\Factory\Factory;
use Enhavo\Bundle\NavigationBundle\Form\Type\NodeContentType;
use Enhavo\Bundle\NavigationBundle\Item\AbstractConfiguration;
use Enhavo\Bundle\NavigationBundle\Item\Item;
use Enhavo\Bundle\AppBundle\DynamicForm\ResolverInterface;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
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

    public function __construct(FormFactoryInterface $formFactory, TypeCollector $collector, $configurations)
    {
        $this->formFactory = $formFactory;

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
        if($this->isContentClass($name)) {
            $formOptions = [
                'item_resolver' => 'enhavo_navigation.resolver.node_resolver',
                'data_class' => Node::class,
                'form_type' => $item->getForm()
            ];
            $form = $this->formFactory->create(NodeContentType::class, $data, array_merge($formOptions, $options));
        } else {
            $formOptions = [
                'item_resolver' => 'enhavo_navigation.resolver.node_resolver',
                'data_class' => Node::class,
            ];
            $form = $this->formFactory->create($item->getForm(), $data, array_merge($formOptions, $options));
        }

        return $form;
    }

    public function resolveFactory($name)
    {
        $item = $this->resolveItem($name);

        if($this->isContentClass($name)) {
            $factoryClass = $item->getFactory();
            /** @var FactoryInterface $factory */
            $factory = new $factoryClass($item->getModel());
            $content = $factory->createNew();
            return new Factory($this->getNodeClass(), $item->getName(), $content);
        } else {
            $factoryClass = $item->getFactory();
            return new $factoryClass($item->getModel(), $item->getName(), null);
        }
    }

    public function resolveFormTemplate($name)
    {
        $item = $this->resolveItem($name);
        return $item->getTemplate();
    }

    private function resolveContent($name)
    {
        $item = $this->resolveItem($name);
        if($this->isContentClass($name)) {
            $class = $item->getModel();
            return new $class;
        }
        return null;
    }

    private function isContentClass($name)
    {
        $item = $this->resolveItem($name);
        return !is_subclass_of($item->getModel(), NodeInterface::class);
    }

    private function getNodeClass()
    {
        return $this->container->getParameter('enhavo_navigation.model.node.class');
    }
}