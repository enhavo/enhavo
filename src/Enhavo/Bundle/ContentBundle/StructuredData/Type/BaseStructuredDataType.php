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

use Enhavo\Bundle\ContentBundle\StructuredData\Context;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataBag;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseStructuredDataType extends AbstractType implements StructuredDataTypeInterface
{
    public function buildData(array $options, object $model, StructuredDataBag $bag, Context $context): void
    {

    }

    public function getTypeName(array $options): ?string
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'groups' => null,
        ]);
    }

    public function getGroups(array $options): array
    {
        if (is_string($options['groups'])) {
            return [$options['groups']];
        } else if (is_array($options['groups'])) {
            return $options['groups'];
        }

        return ['Default'];
    }
}
