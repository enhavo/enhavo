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
use Enhavo\Bundle\BlockBundle\Factory\PictureBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\PictureBlockType as PictureBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Block\PictureBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form' => PictureBlockFormType::class,
            'model' => PictureBlock::class,
            'factory' => PictureBlockFactory::class,
            'template' => 'theme/block/picture.html.twig',
            'label' => 'picture.label.picture',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content', 'simple'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'picture';
    }
}
