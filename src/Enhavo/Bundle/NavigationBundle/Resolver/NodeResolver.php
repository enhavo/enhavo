<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 14:10
 */

namespace Enhavo\Bundle\NavigationBundle\Resolver;

use Enhavo\Bundle\AppBundle\DynamicForm\Item;
use Enhavo\Bundle\AppBundle\DynamicForm\ResolverInterface;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Symfony\Component\Form\FormFactoryInterface;

class NodeResolver implements ResolverInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var array
     */
    private $configuration;

    public function __construct(FormFactoryInterface $formFactory, $configuration)
    {
        $this->formFactory = $formFactory;
        $this->configuration = $configuration;
    }

    public function resolveItemGroup($group)
    {
        return [];
    }

    public function resolveItem($name)
    {
        $item = new Item();
        $item->setType($name);
        $item->setLabel($this->getValue($name, 'label'));
        $item->setTranslationDomain($this->getValue($name, 'translationDomain'));
        return $item;
    }

    public function resolveItems()
    {
        $items = [];
        foreach($this->configuration as $name => $item) {
            $items[] = $this->getItem($name);
        }
        return $items;
    }

    public function resolveFormType($type)
    {
        $formType = $this->getValue($type, 'form');
        $builder = $this->formFactory->create($formType, null, [
            'item_resolver' => 'enhavo_navigation.resolver.item_resolver',
            'data_class' => Node::class
        ]);
        return $builder;
    }

    public function resolveFactory($type)
    {
        $factory = $this->getValue($type, 'factory');
        return new $factory;
    }

    public function resolveFormTemplate($name)
    {
        $template = $this->getValue($name, 'template');
        return $template;
    }

    private function getValue($type, $key)
    {
        if(!array_key_exists($type, $this->configuration)) {
            throw new \Exception(sprintf('Navigation type "%s" does not exist', $type));
        }

        if(!array_key_exists($key, $this->configuration[$type])) {
            throw new \Exception(sprintf('The key "%s" for type "%s" does not exist', $key, $type));
        }

        return $this->configuration[$type][$key];
    }
}