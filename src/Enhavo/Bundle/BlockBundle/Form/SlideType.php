<?php
/**
 * SlideType.php
 *
 * @since 02/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Form;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
        $builder->add('title', TextType::class, array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('url', TextType::class, array(
            'label' => 'slide.form.label.url',
            'translation_domain' => 'EnhavoSliderBundle',
            'attr' => array('class' => 'link-type-external'),
        ));

        $builder->add('text', TextareaType::class, array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('publicationDate', DateType::class, array(
            'label' => 'form.label.publication_date',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('publishedUntil', DateType::class, array(
            'label' => 'form.label.published_until',
            'translation_domain' => 'EnhavoContentBundle',
            'allow_clear' => true
        ));

        $builder->add('public', BooleanType::class, array(
            'label' => 'form.label.public',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('image', MediaType::class, array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_slider_slide';
    }
}
