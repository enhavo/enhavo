<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (!$this->isGroupSelected($options, $context)) {
            return;
        }

        $value = $sourceValue->getValue();
        if (!is_null($value) && !is_scalar($value)) {
            throw new \InvalidArgumentException(sprintf('Duplicate type property only accept scalar values but "%s" given', gettype($value)));
        }

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
        return 'property';
    }
}
