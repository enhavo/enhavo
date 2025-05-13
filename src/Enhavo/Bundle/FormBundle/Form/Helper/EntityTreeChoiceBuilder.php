<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Helper;

use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EntityTreeChoiceBuilder
{
    /**
     * @var EntityTreeChoice[]
     */
    private $choiceTree = [];

    /**
     * @var EntityTreeChoice[]
     */
    private $choiceList = [];

    /**
     * @var PropertyAccess
     */
    private $propertyAccessor;

    /**
     * @var string
     */
    private $parentProperty;

    /**
     * EntityTreeBuilder constructor.
     */
    public function __construct($parentProperty)
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->parentProperty = $parentProperty;
    }

    /**
     * @param ChoiceView[] $choices
     */
    public function build($choices)
    {
        /** @var ChoiceView $choiceView */
        foreach ($choices as $key => $choiceView) {
            $parentData = $this->getParent($choiceView);
            if (null === $parentData) {
                $entityChoice = new EntityTreeChoice($choiceView);
                $this->choiceTree[] = $entityChoice;
                $this->choiceList[] = $entityChoice;
                unset($choices[$key]);
            } else {
                foreach ($this->choiceTree as $choice) {
                    $parentChoice = $this->findByData($choice, $parentData);
                    if ($parentChoice) {
                        $entityChoice = new EntityTreeChoice($choiceView);
                        $parentChoice->addChildren($entityChoice);
                        $this->choiceList[] = $entityChoice;
                        unset($choices[$key]);
                    }
                }
            }
        }

        if (count($choices) > 0) {
            $this->build($choices);
        }
    }

    public function map(FormView $formView)
    {
        /** @var FormView $child */
        foreach ($formView as $child) {
            $value = $child->vars['value'];
            foreach ($this->choiceList as $choice) {
                if ($choice->getChoiceView()->value == $value) {
                    $choice->setFormView($child);
                    break;
                }
            }
        }
    }

    public function getChoiceViews()
    {
        $choiceViews = [];
        foreach ($this->choiceTree as $choice) {
            $this->addToChoiceView($choice, $choiceViews, 0);
        }

        return $choiceViews;
    }

    private function addToChoiceView(EntityTreeChoice $choice, array &$choiceViews, $level)
    {
        $choiceView = $choice->getChoiceView();
        $choiceView->level = $level;
        $choiceViews[] = $choiceView;
        foreach ($choice->getChildren() as $child) {
            $this->addToChoiceView($child, $choiceViews, $level + 1);
        }
    }

    /**
     * @return EntityTreeChoice[]
     */
    public function getChoices()
    {
        return $this->choiceTree;
    }

    private function findByData(EntityTreeChoice $choice, $data)
    {
        if ($this->getData($choice->getChoiceView()) == $data) {
            return $choice;
        }

        foreach ($choice->getChildren() as $child) {
            if ($this->getData($child->getChoiceView()) == $data) {
                return $child;
            }
            $descendant = $this->findByData($child, $data);
            if (null !== $descendant) {
                return $descendant;
            }
        }

        return null;
    }

    private function getData(ChoiceView $view)
    {
        return $view->data;
    }

    private function getParent(ChoiceView $view)
    {
        return $this->propertyAccessor->getValue($this->getData($view), $this->parentProperty);
    }
}
