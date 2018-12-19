<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.11.16
 * Time: 14:20
 */
namespace Enhavo\Bundle\TranslationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TranslationStringType extends AbstractType
{
    /**
     * @var boolean
     */
    protected $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translationKey', TextType::class, array(
            'label' => 'translation.form.label.translationKey',
            'translation_domain' => 'EnhavoTranslationBundle',
        ));
        $builder->add('translationValue', TextType::class, array(
            'label' => 'translation.form.label.translationValue',
            'translation' => $this->translation,
            'translation_domain' => 'EnhavoTranslationBundle',
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\TranslationBundle\Entity\TranslationString'
        ));
    }

    public function getName()
    {
        return 'enhavo_translation_translation_string';
    }
}