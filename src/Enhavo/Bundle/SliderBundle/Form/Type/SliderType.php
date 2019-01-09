<?php
/**
 * SlideType.php
 *
 * @since 02/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SliderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderType extends AbstractType
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_slider_slider';
    }
} 