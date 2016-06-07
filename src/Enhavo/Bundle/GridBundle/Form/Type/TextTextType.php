<?php
/**
 * TextTextType.php
 *
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\GridBundle\Item\Type\Text;
use Enhavo\Bundle\GridBundle\Entity\TextText;

class TextTextType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('titleLeft', 'text', array(
            'label' => 'textText.form.label.title_left',
            'translation_domain' => 'EnhavoGridBundle',
        ));

        $builder->add('textLeft', 'enhavo_wysiwyg', array(
            'label' => 'textText.form.label.text_left',
            'translation_domain' => 'EnhavoGridBundle',
        ));

        $builder->add('titleRight', 'text', array(
            'label' => 'textText.form.label.title_right',
            'translation_domain' => 'EnhavoGridBundle',
        ));

        $builder->add('textRight', 'enhavo_wysiwyg', array(
            'label' => 'textText.form.label.text_right',
            'translation_domain' => 'EnhavoGridBundle'
        ));

        $builder->add('layout', 'choice', array(
            'label' => 'textText.form.label.layout',
            'translation_domain' => 'EnhavoGridBundle',
            'choices'   => array(
                TextText::LAYOUT_1_1 => 'textText.form.label.1_1',
                TextText::LAYOUT_1_2 => 'textText.form.label.1_2',
                TextText::LAYOUT_2_1 => 'textText.form.label.2_1'
            ),
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\GridBundle\Entity\TextText'
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_item_text_text';
    }
} 