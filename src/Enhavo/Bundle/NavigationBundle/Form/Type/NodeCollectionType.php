<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:42
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItem;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItemManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeCollectionType extends AbstractType
{
    /** @var string */
    private $class;

    /** @var NavItemManager */
    private $navItemManager;

    /**
     * NodeCollectionType constructor.
     * @param string $class
     * @param NavItemManager $navItemManager
     */
    public function __construct(string $class, NavItemManager $navItemManager)
    {
        $this->class = $class;
        $this->navItemManager = $navItemManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'block.label.blocks',
            'translation_domain' => 'EnhavoBlockBundle',
            'entry_types' => $this->getEntryTypes(),
            'entry_types_options' => $this->getEntryTypesOptions(),
            'entry_type_name' => 'name',
            'entry_type_resolver' => function(NodeInterface $node) {
                return $node->getName();
            },
            'prototype_storage' => 'enhavo_navigation',
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

    public function getParent()
    {
        return PolyCollectionType::class;
    }

    private function getEntryTypes()
    {
        $types = [];
        /** @var NavItem $navItem */
        foreach($this->navItemManager->getNavItems() as $key => $navItem) {
            $types[$key] = NodeType::class;
        }
        return $types;
    }

    private function getEntryTypesOptions()
    {
        $types = [];
        /** @var NavItem $navItem */
        foreach($this->navItemManager->getNavItems() as $key => $navItem) {
            $types[$key] = [
                'subject_type' => $navItem->getForm(),
                'subject_type_options' => $navItem->getFormOptions(),
                'label' => $navItem->getLabel(),
                'translation_domain' => $navItem->getTranslationDomain(),
            ];
        }
        return $types;
    }
}
