<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

abstract class AbstractVueType implements VueTypeInterface
{
    public function buildView(FormView $view, VueData $data)
    {

    }

    public function finishView(FormView $view, VueData $data)
    {

    }
}
