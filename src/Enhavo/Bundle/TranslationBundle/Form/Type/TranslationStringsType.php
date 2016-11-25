<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.11.16
 * Time: 14:20
 */
namespace Enhavo\Bundle\TranslationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TranslationStringsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translationKey', 'text', array(
            'label' => 'translation.form.label.translationKey',
            'translation_domain' => 'EnhavoTranslationBundle',
        ));
        $builder->add('translationValue', 'text', array(
            'label' => 'translation.form.label.translationValue',
            'translation' => true,
            'translation_domain' => 'EnhavoTranslationBundle',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\TranslationBundle\Entity\TranslationStrings'
        ));
    }

    public function getName()
    {
        return 'enhavo_translation_strings';
    }
}