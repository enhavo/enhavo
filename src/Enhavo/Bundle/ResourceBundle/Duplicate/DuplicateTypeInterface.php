<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface DuplicateTypeInterface extends TypeInterface
{
    public function isApplicable($options, SourceValue $sourceValue, TargetValue $targetValue, $context): bool;

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void;

    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void;

    public function configureOptions(OptionsResolver $resolver);
}
