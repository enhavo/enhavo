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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlideType extends AbstractType
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'textarea', array(
            'label' => 'form.label.title'
        ));

        $builder->add('url', 'text', array(
            'label' => 'form.label.url',
            'attr' => array('class' => 'link-type-external'),
        ));

        $builder->add('text', 'textarea', array(
            'label' => 'form.label.text'
        ));

        $builder->add('public', 'enhavo_boolean', array(
            'label' => 'form.label.public'
        ));

        $builder->add('image', 'enhavo_files', array(
            'label' => 'form.label.image'
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
        return 'enhavo_slider_slide';
    }
} 