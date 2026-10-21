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

use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property DuplicateTypeInterface   $type
 * @property DuplicateTypeInterface[]          $parents
 * @property DuplicateTypeExtensionInterface[] $extensions
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
            foreach ($this->extensions as $extension) {
                if ($this->isExtendable($parent, $extension)) {
                    $extension->duplicate($this->options, $sourceValue, $targetValue, $context);
                }
            }
        }

        $this->type->duplicate($this->options, $sourceValue, $targetValue, $context);
        foreach ($this->extensions as $extension) {
            if ($this->isExtendable($this->type, $extension)) {
                $extension->duplicate($this->options, $sourceValue, $targetValue, $context);
            }
        }

        foreach ($this->parents as $parent) {
            $parent->finish($this->options, $sourceValue, $targetValue, $context);
            foreach ($this->extensions as $extension) {
                if ($this->isExtendable($parent, $extension)) {
                    $extension->finish($this->options, $sourceValue, $targetValue, $context);
                }
            }
        }

        $this->type->finish($this->options, $sourceValue, $targetValue, $context);
        foreach ($this->extensions as $extension) {
            if ($this->isExtendable($this->type, $extension)) {
                $extension->finish($this->options, $sourceValue, $targetValue, $context);
            }
        }

        return $targetValue->getValue();
    }

    public function diff(SourceValue $oldValue, SourceValue $newValue, Diff $diff, array $context = []): void
    {
        if (!$this->options['diff']) {
            return;
        }

        $diff->enter($diff->getFormatter()->getLabel($this->options['label'], $this->options['translation_domain'], $newValue->getPropertyName() ?? $oldValue->getPropertyName()));
        $this->type->diff($this->options, $oldValue, $newValue, $diff, $context);
        $diff->leave();
    }
}
