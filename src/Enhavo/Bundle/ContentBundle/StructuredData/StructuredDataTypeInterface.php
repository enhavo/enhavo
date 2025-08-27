<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData;

use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface StructuredDataTypeInterface extends TypeInterface
{
    public function buildData(array $options, object $model, StructuredDataBag $bag, Context $context): void;

    public function getTypeName(array $options): ?string;

    public function configureOptions(OptionsResolver $resolver);
}
