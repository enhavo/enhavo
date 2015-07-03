<?php
/**
 * TextTextType.php
 *
 */

namespace Enhavo\Bundle\ContentGridBundle\Form\Type;

use Enhavo\Bundle\ContentGridBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\ContentGridBundle\Item\Type\Text;

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
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\ContentGridBundle\Entity\TextText'
        ));
    }

    public function getName()
    {
        return 'enhavo_content_grid_item_text_text';
    }
} 