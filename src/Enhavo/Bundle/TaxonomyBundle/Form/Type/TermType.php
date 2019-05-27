<?php

namespace Enhavo\Bundle\TaxonomyBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermType extends AbstractType
{
    /**
     * @var string $dataClass
     */
    private $dataClass;

    /**
     * TermType constructor.
     * @param $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'form.label.name',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('text', WysiwygType::class, array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
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
}
