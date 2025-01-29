<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08/06/14
 * Time: 16:32
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormView;

class UuidType extends AbstractVueType
{
    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['uuid'] = true;
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}
