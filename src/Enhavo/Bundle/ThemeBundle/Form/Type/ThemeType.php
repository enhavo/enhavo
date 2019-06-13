<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-13
 * Time: 17:04
 */

namespace Enhavo\Bundle\ThemeBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active', BooleanType::class, [
            'label' => 'active',
        ]);
    }
}
