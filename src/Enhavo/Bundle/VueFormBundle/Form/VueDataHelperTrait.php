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

use Enhavo\Bundle\VueFormBundle\Exception\VueFormException;
use Symfony\Component\Form\FormView;

trait VueDataHelperTrait
{
    protected function getVueData(FormView $view): VueData
    {
        if (!isset($view->vars['vue_data'])) {
            throw VueFormException::missingVueData();
        }

        $data = $view->vars['vue_data'];

        if (!$data instanceof VueData) {
            throw VueFormException::missingVueData();
        }

        return $data;
    }
}
