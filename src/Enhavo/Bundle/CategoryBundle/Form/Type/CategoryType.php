<?php

namespace Enhavo\Bundle\CategoryBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * @var $dataClass
     */
    protected $dataClass;

    protected $translation;

    public function __construct($dataClass, $translation)
    {
        $this->dataClass = $dataClass;
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'translation' => $this->translation
        ));

        $builder->add('text', WysiwygType::class, array(
            'translation' => $this->translation
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('slug', TextType::class, array(
            'label' => 'form.label.slug',
            'translation_domain' => 'EnhavoAppBundle'
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_category_category';
    }
}
