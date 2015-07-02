<?php
/**
 * SlideOrderType.php
 *
 * @since 02/11/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace enhavo\SliderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SlideOrderType extends AbstractType
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('order', 'hidden', array(
            'attr' => array(
                'data-sort-order' => ''
            )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->class
        ));
    }

    public function getName()
    {
        return 'enhavo_slider_slide_order';
    }
} 