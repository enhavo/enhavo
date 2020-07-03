<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-21
 * Time: 22:27
 */

namespace Enhavo\Bundle\FormBundle\Prototype;

use Symfony\Component\Form\FormView;

class PrototypeView
{
    /** @var Prototype */
    private $prototype;

    /** @var FormView */
    private $formView;

    public function __construct(Prototype $prototype)
    {
        $this->prototype = $prototype;
    }

    private function load()
    {
        if($this->formView === null) {
            $this->formView = $this->prototype->getForm()->createView();
        }
    }

    public function getStorage()
    {
        return $this->prototype->getStorageName();
    }

    public function getName()
    {
        return $this->prototype->getName();
    }

    public function getParameters()
    {
        return $this->prototype->getParameters();
    }

    public function getVars()
    {
        $this->load();
        return $this->formView->vars;
    }

    public function getFormView()
    {
        $this->load();
        return $this->formView;
    }
}
