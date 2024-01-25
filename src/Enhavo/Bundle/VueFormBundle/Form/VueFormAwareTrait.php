<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Enhavo\Bundle\VueFormBundle\Exception\VueFormException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

trait VueFormAwareTrait
{
    protected VueForm $vueForm;

    public function setVueForm(VueForm $vueForm): void
    {
        $this->vueForm = $vueForm;
    }

    public function getVueForm(): VueForm
    {
        return $this->vueForm;
    }

    public function createVueForm(FormInterface $form): array
    {
        return $this->vueForm->createData($form->createView());
    }
}
