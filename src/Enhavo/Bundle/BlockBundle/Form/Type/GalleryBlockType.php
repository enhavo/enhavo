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

use Enhavo\Bundle\BlockBundle\Model\Block\GalleryBlock;
use Enhavo\Bundle\FormBundle\Form\Type\HeadLineType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryBlockType extends AbstractType
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

        $builder->add('files', MediaType::class, [
            'label' => 'gallery.form.label.pictures',
            'translation_domain' => 'EnhavoBlockBundle',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GalleryBlock::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_gallery';
    }
}
