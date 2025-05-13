<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem\Type;

use Enhavo\Bundle\NavigationBundle\Entity\Content;
use Enhavo\Bundle\NavigationBundle\Factory\ContentFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\ContentFormType;
use Enhavo\Bundle\NavigationBundle\NavItem\AbstractNavItemType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentNavItemType extends AbstractNavItemType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'factory' => ContentFactory::class,
            'model' => Content::class,
            'form' => ContentFormType::class,
        ]);

        $resolver->setRequired('content_form');

        $resolver->setNormalizer('form_options', function ($options, $value) {
            return array_merge($value, ['content_form' => $options['content_form']]);
        });
    }

    public static function getName(): ?string
    {
        return 'content';
    }
}
