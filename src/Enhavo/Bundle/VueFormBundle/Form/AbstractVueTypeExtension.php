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

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractVueTypeExtension extends AbstractTypeExtension
{
    use VueDataHelperTrait;

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->buildVueData($view, $this->getVueData($view), $options);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $this->finishVueData($view, $this->getVueData($view), $options);
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
    }

    public function finishVueData(FormView $view, VueData $data, array $options)
    {
    }
}
