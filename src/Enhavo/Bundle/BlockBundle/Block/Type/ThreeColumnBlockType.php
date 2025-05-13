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
use Enhavo\Bundle\BlockBundle\Factory\ThreeColumnBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\ThreeColumnBlockType as ThreeColumnBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Column\ThreeColumnBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => ThreeColumnBlock::class,
            'form' => ThreeColumnBlockFormType::class,
            'factory' => ThreeColumnBlockFactory::class,
            'template' => 'theme/block/three-column.html.twig',
            'label' => 'three_column.label.three_column',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'layout'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'three_column';
    }
}
