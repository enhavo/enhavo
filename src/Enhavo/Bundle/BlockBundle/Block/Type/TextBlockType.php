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
use Enhavo\Bundle\BlockBundle\Factory\TextBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TextBlockType as TextBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Block\TextBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => TextBlock::class,
            'form' => TextBlockFormType::class,
            'factory' => TextBlockFactory::class,
            'template' => 'theme/block/text.html.twig',
            'label' => 'text.label.text',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content', 'simple'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'text';
    }
}
