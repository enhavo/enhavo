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
use Enhavo\Bundle\BlockBundle\Factory\OneColumnBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\OneColumnBlockType as OneColumnBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Column\OneColumnBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => OneColumnBlock::class,
            'form' => OneColumnBlockFormType::class,
            'factory' => OneColumnBlockFactory::class,
            'template' => 'theme/block/one-column.html.twig',
            'label' => 'one_column.label.one_column',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'layout'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'one_column';
    }
}
