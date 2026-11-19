<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;

class ModelDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    ) {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (null === $sourceValue->getValue()) {
            $targetValue->setValue(null);

            return;
        }

        $value = $this->duplicateFactory->duplicate($sourceValue->getValue(), $targetValue->getValue(), $context);
        $targetValue->setValue($value);
    }

    public function diff($options, SourceValue $oldValue, SourceValue $newValue, Diff $diff, $context): void
    {
        $old = $oldValue->getValue();
        $new = $newValue->getValue();
        if (null === $old && null === $new) {
            return;
        }

        $diff->diffObject($old, $new, $context);
    }

    public static function getName(): ?string
    {
        return 'model';
    }
}
