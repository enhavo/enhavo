<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData\Type;

use Enhavo\Bundle\ContentBundle\StructuredData\AbstractStructuredDataType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleStructuredDataType extends AbstractStructuredDataType
{
    private static array $properties = [
        'articleBody',
        'articleSection',
        'backstory',
        'pageEnd',
        'pageStart',
        'pagination',
        'speakable',
        'wordCount',
    ];

    public function getTypeName(array $options): ?string
    {
        return 'Article';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->addAllowedValues('property', self::$properties);
    }

    public static function getParentType(): ?string
    {
        return CreativeWorkStructuredDataType::class;
    }

    public static function getName(): ?string
    {
        return 'article';
    }
}
