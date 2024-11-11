<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Value;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StringDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, Value $newValue, Value $originalValue, $context): void
    {
        if ($originalValue->getValue() === null) {
            $newValue->setValue(null);
            return;
        }

        $value = $options['prefix'].$originalValue->getValue().$options['postfix'];
        $newValue->setValue($value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'group' => null,
            'prefix' => null,
            'postfix' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'string';
    }
}
