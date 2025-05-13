<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\TextPictureBlock;
use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\HeadLineType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', HeadLineType::class, [
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('text', WysiwygType::class, [
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('caption', TextType::class, [
            'label' => 'textPicture.form.label.caption',
            'translation_domain' => 'EnhavoBlockBundle',
        ]);

        $builder->add('file', MediaType::class, [
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false,
        ]);

        $builder->add('textLeft', BooleanType::class, [
            'label' => 'textPicture.form.label.position',
            'translation_domain' => 'EnhavoBlockBundle',
            'choice_translation_domain' => 'EnhavoBlockBundle',
            'choices' => [
                'textPicture.form.label.picture_left-text_right' => BooleanType::VALUE_FALSE,
                'textPicture.form.label.text_left-picture_right' => BooleanType::VALUE_TRUE,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('float', BooleanType::class, [
            'label' => 'textPicture.form.label.float',
            'translation_domain' => 'EnhavoBlockBundle',
        ]);

        $builder->add('layout', ChoiceType::class, [
            'label' => 'textText.form.label.layout',
            'translation_domain' => 'EnhavoBlockBundle',
            'choices' => [
                '1:1' => TextPictureBlock::LAYOUT_1_1,
                '1:2' => TextPictureBlock::LAYOUT_1_2,
                '2:1' => TextPictureBlock::LAYOUT_2_1,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TextPictureBlock::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_text_picture';
    }
}
