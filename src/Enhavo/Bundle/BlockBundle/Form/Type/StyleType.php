<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.10.18
 * Time: 15:21
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

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
        foreach($styles as $style) {
            $this->styles[$style['label']] = $style['value'];
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'column.label.style.label',
            'translation_domain' => 'EnhavoGridBundle',
            'choices_as_values' => true,
            'choices' => $this->styles,
            'placeholder' => 'column.label.style.placeholder'
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}