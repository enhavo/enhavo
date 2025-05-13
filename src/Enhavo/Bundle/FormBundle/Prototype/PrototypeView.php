<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        if (null === $this->formView) {
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
