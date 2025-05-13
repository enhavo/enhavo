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
use Enhavo\Bundle\BlockBundle\Factory\TemplateBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TemplateBlockType as TemplateBlockFormType;
use Enhavo\Bundle\BlockBundle\Model\Block\TemplateBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => TemplateBlock::class,
            'form' => TemplateBlockFormType::class,
            'factory' => TemplateBlockFactory::class,
            'template' => 'theme/block/template.html.twig',
            'label' => 'template.label.template',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }
}
