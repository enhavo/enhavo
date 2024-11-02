<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\Value;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    )
    {
    }

    public function duplicate($options, Value $newValue, Value $originalValue, $context): void
    {
        if ($originalValue->getValue() === null) {
            $newValue->setValue(null);
            return;
        }

        $value = $this->duplicateFactory->duplicate($originalValue->getValue(), $context);
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
        return 'model';
    }
}
