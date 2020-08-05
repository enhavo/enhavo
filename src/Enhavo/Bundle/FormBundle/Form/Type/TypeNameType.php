<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-25
 * Time: 22:16
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TypeNameType extends AbstractType
{
    public function getParent()
    {
        return HiddenType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-type-name'] = '';
    }
}
