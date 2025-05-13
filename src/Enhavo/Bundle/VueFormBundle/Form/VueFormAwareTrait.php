<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
