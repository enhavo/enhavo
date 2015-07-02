<?php
/**
 * SlideType.php
 *
 * @since 02/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\SliderBundle\Form\Type;

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
            'label' => ' ',
            'attr' => array('class' => 'link-type-external'),
        ));

        $builder->add('text', 'textarea', array(
            'label' => 'form.label.text'
        ));

        $builder->add('link_type', 'text', array(
            'label' => 'form.label.link'
        ));

        $builder->add('public', 'choice', array(
            'label' => 'form.label.public',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('image', 'esperanto_files', array(
            'label' => 'form.label.image',
            'information' => array('Bilder in der Größe 640x360 Pixel (oder ein vielfaches davon) hochladen'),
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
        return 'esperanto_slider_slide';
    }
} 