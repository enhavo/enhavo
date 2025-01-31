<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:42
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItemManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeCollectionType extends AbstractType
{
    /** @var string */
    private $class;

    /** @var string */
    private $formClass;

    /** @var NavItemManager */
    private $navItemManager;

    /**
     * NodeCollectionType constructor.
     * @param string $class
     * @param string $formClass
     * @param NavItemManager $navItemManager
     */
    public function __construct(string $class, string $formClass, NavItemManager $navItemManager)
    {
        $this->class = $class;
        $this->formClass = $formClass;
        $this->navItemManager = $navItemManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'node.label.items',
            'translation_domain' => 'EnhavoNavigationBundle',
            'entry_types' => $this->getEntryTypes(),
            'entry_types_options' => $this->getEntryTypesOptions(),
            'entry_types_prototype_data' => $this->getEntryTypesPrototypeData(),
            'entry_type_name' => 'name',
            'entry_type_resolver' => function(NodeInterface $node) {
                return $node->getName();
            },
            'prototype_storage' => 'enhavo_navigation',
            'allow_add' => true,
            'allow_delete' => true,
            'custom_name_property' => 'label'
        ]);
    }

    public function getParent()
    {
        return PolyCollectionType::class;
    }

    private function getEntryTypes()
    {
        $types = [];
        foreach($this->navItemManager->getNavItems() as $key => $navItem) {
            $types[$key] = $this->formClass;
        }
        return $types;
    }

    private function getEntryTypesOptions()
    {
        $types = [];
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

    private function getEntryTypesPrototypeData()
    {
        $data = [];
        foreach($this->navItemManager->getNavItems() as $key => $item) {
            /** @var NodeInterface $node */
            $node = new $this->class;
            $node->setName($key);
            $modelClass = $item->getModel();
            if ($modelClass !== null) {
                $node->setSubject(new $modelClass);
            }
            $data[$key] = $node;
        }
        return $data;
    }
}
