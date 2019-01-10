<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 17.10.18
 * Time: 17:31
 */

namespace Enhavo\Bundle\AppBundle\Form\Helper;

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
     * @param $choiceView
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

    /**
     * @param EntityTreeChoice $children
     */
    public function addChildren(EntityTreeChoice $children)
    {
        $this->children[] = $children;
    }

    /**
     * @param EntityTreeChoice $children
     */
    public function removeChildren(EntityTreeChoice $children)
    {
        if (false !== $key = array_search($children, $this->children, true)) {
            array_splice($this->children, $key, 1);
        }
    }

    /**
     * @return mixed
     */
    public function getFormView()
    {
        return $this->formView;
    }

    /**
     * @param mixed $formView
     */
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