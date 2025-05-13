<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\Column;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WidthType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'column.label.width.label',
            'translation_domain' => 'EnhavoBlockBundle',
            'choices_as_values' => true,
            'choices' => [
                'column.label.width.container' => Column::WIDTH_CONTAINER,
                'column.label.width.full' => Column::WIDTH_FULL,
            ],
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
