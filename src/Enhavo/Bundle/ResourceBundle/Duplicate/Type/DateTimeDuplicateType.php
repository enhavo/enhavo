<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $targetValue->setValue(new \DateTime());
    }

    public static function getName(): ?string
    {
        return 'datetime';
    }
}
