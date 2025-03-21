<?php

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
