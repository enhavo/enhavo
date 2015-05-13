<?php
/**
 * TextTextType.php
 *
 */

namespace esperanto\ContentBundle\Form\Type;

use esperanto\ContentBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use esperanto\ContentBundle\Item\Type\Text;

class TextTextType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');
        $builder->add('textLeft', 'wysiwyg');
        $builder->add('textRight', 'wysiwyg');
        $builder->add('titleLeft', 'text');
        $builder->add('titleRight', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Entity\TextText'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_item_text_text';
    }
} 