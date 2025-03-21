<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StringDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if ($sourceValue->getValue() === null) {
            $targetValue->setValue(null);
            return;
        }

        $value = $options['prefix'].$sourceValue->getValue().$options['postfix'];
        $targetValue->setValue($value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'prefix' => null,
            'postfix' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'string';
    }
}
