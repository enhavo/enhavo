<?php
/**
 * SlideOrderType.php
 *
 * @since 02/11/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SliderBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SlideOrderType extends AbstractType
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('order', PositionType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_slider_slide_order';
    }
} 