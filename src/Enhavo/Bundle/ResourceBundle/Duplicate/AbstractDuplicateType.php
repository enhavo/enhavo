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

use Enhavo\Bundle\ResourceBundle\Duplicate\Type\BaseDuplicateType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property DuplicateTypeInterface $parent
 */
abstract class AbstractDuplicateType extends AbstractType implements DuplicateTypeInterface
{
    public function isApplicable($options, SourceValue $sourceValue, TargetValue $targetValue, $context): bool
    {
        return $this->parent->isApplicable($options, $sourceValue, $targetValue, $context);
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
    }

    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
    }

    public static function getParentType(): ?string
    {
        return BaseDuplicateType::class;
    }
}
