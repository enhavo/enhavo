<?php

namespace Enhavo\Bundle\BlockBundle\Duplicate;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;

class BlockNodeDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    )
    {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (!$this->isGroupSelected($options, $context)) {
            return;
        }

        $node = $sourceValue->getValue();
        if ($node === null) {
            $targetValue->setValue(null);
        } else {

        }
    }

    public function finish($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (!$this->isGroupSelected($options, $context)) {
            return;
        }
    }
}
