<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.10.18
 * Time: 15:21
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Model\Column\Column;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WidthType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'column.label.width.label',
            'translation_domain' => 'EnhavoGridBundle',
            'choices_as_values' => true,
            'choices' => [
                'column.label.width.full' => Column::WIDTH_FULL,
                'column.label.width.container' => Column::WIDTH_CONTAINER
            ]
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}