<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Factory\TextPictureBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TextPictureBlockType as TextPictureBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Block\TextPictureBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => TextPictureBlock::class,
            'form' => TextPictureBlockFormType::class,
            'factory' => TextPictureBlockFactory::class,
            'template' => 'theme/block/text-picture.html.twig',
            'label' => 'textPicture.label.textPicture',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content', 'simple'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'text_picture';
    }
}
