<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormInterface;

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
