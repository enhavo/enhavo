<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    )
    {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if ($sourceValue->getValue() === null) {
            $targetValue->setValue(null);
            return;
        }

        $value = $this->duplicateFactory->duplicate($sourceValue->getValue(), $targetValue->getValue(), $context);
        $targetValue->setValue($value);
    }

    public static function getName(): ?string
    {
        return 'model';
    }
}
