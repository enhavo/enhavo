<?php
/**
 * TextPictureType.php
 *
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\BooleanType;
use Enhavo\Bundle\GridBundle\Entity\TextPicture;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('text', 'enhavo_wysiwyg', array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('caption', 'text', array(
            'label' => 'textPicture.form.label.caption',
            'translation_domain' => 'EnhavoGridBundle',
            'translation' => $this->translation
        ));

        $builder->add('file', 'enhavo_files', array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('textLeft', 'enhavo_boolean', array(
            'label' => 'textPicture.form.label.position',
            'translation_domain' => 'EnhavoGridBundle',
            'choices' => array(
                BooleanType::VALUE_FALSE => 'textPicture.form.label.picture_left-text_right',
                BooleanType::VALUE_TRUE => 'textPicture.form.label.text_left-picture_right'
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('float', 'enhavo_boolean', array(
            'label' => 'textPicture.form.label.float',
            'translation_domain' => 'EnhavoGridBundle'
        ));

        $builder->add('layout', 'choice', array(
            'label' => 'textText.form.label.layout',
            'translation_domain' => 'EnhavoGridBundle',
            'choices'   => array(
                TextPicture::LAYOUT_1_1 => 'textPicture.form.label.1_1',
                TextPicture::LAYOUT_1_2 => 'textPicture.form.label.1_2',
                TextPicture::LAYOUT_2_1 => 'textPicture.form.label.2_1'
            ),
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
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