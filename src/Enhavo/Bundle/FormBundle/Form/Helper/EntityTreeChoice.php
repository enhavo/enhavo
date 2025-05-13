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

class EntityTreeChoice
{
    /**
     * @var ChoiceView
     */
    private $choiceView;

    /**
     * @var FormView
     */
    private $formView;

    /**
     * @var EntityTreeChoice[]
     */
    private $children = [];

    /**
     * EntityTreeChoice constructor.
     */
    public function __construct(ChoiceView $choiceView)
    {
        $this->choiceView = $choiceView;
    }

    /**
     * @return ChoiceView
     */
    public function getChoiceView()
    {
        return $this->choiceView;
    }

    public function addChildren(EntityTreeChoice $children)
    {
        $this->children[] = $children;
    }

    public function removeChildren(EntityTreeChoice $children)
    {
        if (false !== $key = array_search($children, $this->children, true)) {
            array_splice($this->children, $key, 1);
        }
    }

    public function getFormView()
    {
        return $this->formView;
    }

    public function setFormView($formView)
    {
        $this->formView = $formView;
    }

    /**
     * @return EntityTreeChoice[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
