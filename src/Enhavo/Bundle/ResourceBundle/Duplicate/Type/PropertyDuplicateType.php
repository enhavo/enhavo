<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Value;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, Value $newValue, Value $originalValue, $context): void
    {
        $value = $originalValue->getValue();
        if (!is_null($value) && !is_scalar($value)) {
            throw new \InvalidArgumentException(sprintf('Duplicate type property only accept scalar values but "%s" given', gettype($value)));
        }

        $newValue->setValue($value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'group' => null
        ]);
    }

    public static function getName(): ?string
    {
        return 'property';
    }
}
