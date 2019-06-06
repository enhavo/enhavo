<?php
/**
 * TextPictureType.php
 *
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\GridBundle\Model\Item\TextPictureItem;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureItemType extends AbstractType
{
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('text', WysiwygType::class, array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('caption', TextType::class, array(
            'label' => 'textPicture.form.label.caption',
            'translation_domain' => 'EnhavoGridBundle',
            'translation' => $this->translation
        ));

        $builder->add('file', MediaType::class, array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false
        ));

        $builder->add('textLeft', BooleanType::class, array(
            'label' => 'textPicture.form.label.position',
            'translation_domain' => 'EnhavoGridBundle',
            'choices' => array(
                'textPicture.form.label.picture_left-text_right' => BooleanType::VALUE_FALSE,
                'textPicture.form.label.text_left-picture_right' => BooleanType::VALUE_TRUE
            ),
            'expanded' => true,
            'multiple' => false
        ));

        $builder->add('float', BooleanType::class, array(
            'label' => 'textPicture.form.label.float',
            'translation_domain' => 'EnhavoGridBundle'
        ));

        $builder->add('layout', ChoiceType::class, array(
            'label' => 'textText.form.label.layout',
            'translation_domain' => 'EnhavoGridBundle',
            'choices' => [
                '1:1' => TextPictureItem::LAYOUT_1_1,
                '1:2' => TextPictureItem::LAYOUT_1_2,
                '2:1' => TextPictureItem::LAYOUT_2_1
            ],
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TextPictureItem::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_text_picture_item';
    }
} 
