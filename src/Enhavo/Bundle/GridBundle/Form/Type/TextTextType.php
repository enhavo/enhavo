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

class TextTextType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title'
        ));

        $builder->add('titleLeft', 'text', array(
            'label' => 'form.label.title_left'
        ));

        $builder->add('textLeft', 'wysiwyg', array(
            'label' => 'form.label.text_left'
        ));

        $builder->add('titleRight', 'text', array(
            'label' => 'form.label.title_right'
        ));

        $builder->add('textRight', 'wysiwyg', array(
            'label' => 'form.label.text_right'
        ));

        $builder->add('textLayout', 'choice', array(
            'label' => 'form.label.textLayout',
            'choices'   => array(
                '0' => 'label.1:2',
                '1' => 'label.2:1',
                '2' => 'label.50:50'
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