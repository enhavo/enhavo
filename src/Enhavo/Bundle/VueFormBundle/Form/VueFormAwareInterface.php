<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormInterface;

interface VueFormAwareInterface
{
    public function setVueForm(VueForm $vueForm): void;

    public function getVueForm(): VueForm;

    public function createVueForm(FormInterface $form);
}
