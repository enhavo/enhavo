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

interface VueFormAwareInterface
{
    public function setVueForm(VueForm $vueForm): void;

    public function getVueForm(): VueForm;

    public function createVueForm(FormInterface $form);
}
