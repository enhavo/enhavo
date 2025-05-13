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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StyleType extends AbstractType
{
    /**
     * @var string
     */
    private $styles;

    public function __construct($styles)
    {
        $this->styles = [];
        foreach ($styles as $style) {
            $this->styles[$style['label']] = $style['value'];
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'column.label.style.label',
            'translation_domain' => 'EnhavoBlockBundle',
            'choices_as_values' => true,
            'choices' => $this->styles,
            'placeholder' => 'column.label.style.placeholder',
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
