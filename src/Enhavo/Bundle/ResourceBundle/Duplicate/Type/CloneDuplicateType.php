<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CloneDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (!$this->isGroupSelected($options, $context)) {
            return;
        }

        if ($sourceValue->getValue() === null) {
            $targetValue->setValue(null);
            return;
        }

        $value = clone $sourceValue->getValue();
        $targetValue->setValue($value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'groups' => null
        ]);
    }

    public static function getName(): ?string
    {
        return 'clone';
    }
}
