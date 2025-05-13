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

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property DuplicateTypeInterface   $type
 * @property DuplicateTypeInterface[] $parents
 */
class Duplicate extends AbstractContainerType
{
    public function isApplicable(SourceValue $sourceValue, TargetValue $targetValue, array $context = []): bool
    {
        return $this->type->isApplicable($this->options, $sourceValue, $targetValue, $context);
    }

    public function duplicate(SourceValue $sourceValue, TargetValue $targetValue, array $context = []): mixed
    {
        foreach ($this->parents as $parent) {
            $parent->duplicate($this->options, $sourceValue, $targetValue, $context);
        }

        $this->type->duplicate($this->options, $sourceValue, $targetValue, $context);

        foreach ($this->parents as $parent) {
            $parent->finish($this->options, $sourceValue, $targetValue, $context);
        }

        $this->type->finish($this->options, $sourceValue, $targetValue, $context);

        return $targetValue->getValue();
    }
}
