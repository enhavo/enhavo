<?php
/**
 * TextPictureType.php
 *
 */

namespace esperanto\ContentBundle\Form\Type;

use esperanto\ContentBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use esperanto\ContentBundle\Item\Type\Text;

class TextPictureType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', 'wysiwyg');

        $builder->add('title', 'text');

        $builder->add('textLeft', 'choice', array(
            'label' => 'form.label.textLeft',
            'choices'   => array(
                '1' => 'label.text_left-picture_right',
                '0' => 'label.picture_left-text_right'
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('files', 'esperanto_files');

        $builder->add('frame', 'choice', array(
            'label' => 'form.label.public',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Entity\TextPicture'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_item_textpicture';
    }
} 