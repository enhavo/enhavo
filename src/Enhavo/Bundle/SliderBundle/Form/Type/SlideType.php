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
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('url', 'text', array(
            'label' => 'slide.form.label.url',
            'translation_domain' => 'EnhavoSliderBundle',
            'attr' => array('class' => 'link-type-external'),
        ));

        $builder->add('text', 'textarea', array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('publicationDate', 'enhavo_date', array(
            'label' => 'form.label.publication_date',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('publishedUntil', 'enhavo_date', array(
            'label' => 'form.label.published_until',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('public', 'enhavo_boolean', array(
            'label' => 'form.label.public',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('image', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
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