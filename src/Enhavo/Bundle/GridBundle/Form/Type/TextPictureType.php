<?php
/**
 * TextPictureType.php
 *
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\BooleanType;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\GridBundle\Item\Type\Text;

class TextPictureType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('text', 'enhavo_wysiwyg', array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('file', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('frame', 'enhavo_boolean', array(
            'label' => 'textPicture.form.label.frame',
            'translation_domain' => 'EnhavoGridBundle'
        ));

        $builder->add('textLeft', 'enhavo_boolean', array(
            'label' => 'textPicture.form.label.position',
            'translation_domain' => 'EnhavoGridBundle',
            'choices' => array(
                BooleanType::VALUE_TRUE => 'textPicture.form.label.text_left-picture_right',
                BooleanType::VALUE_FALSE => 'textPicture.form.label.picture_left-text_right'
            ),
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\GridBundle\Entity\TextPicture'
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_item_text_picture';
    }
} 