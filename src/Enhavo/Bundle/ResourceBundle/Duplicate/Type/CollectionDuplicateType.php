<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CollectionDuplicateType extends AbstractDuplicateType
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
        } else if ($options['by_reference']) {
            $propertyAccessor = new PropertyAccessor();
            $target = $targetValue->getParent();
            $values = [];
            foreach ($sourceValue->getValue() as $key => $item) {
                $values[] = $this->duplicateFactory->duplicate($item, null, $context);
            }
            $propertyAccessor->setValue($target, $targetValue->getPropertyName(), $values);
            $collection = $propertyAccessor->getValue($target, $targetValue->getPropertyName());
            $targetValue->setValue($collection);
        } else if (is_array($sourceValue->getValue())) {
            $collection = [];
            foreach ($sourceValue->getValue() as $key => $item) {
                $target = null;
                if ($options['map_target']) {
                    $target = is_array($targetValue->getOriginalValue()) ? $targetValue->getOriginalValue()[$key] ?? null : null;
                }
                $collection[$key] = $this->duplicateFactory->duplicate($item, $target, $context);
            }
            $targetValue->setValue($collection);
        } else if ($sourceValue->getValue() instanceof Collection) {
            $collection = new ArrayCollection();
            foreach ($sourceValue->getValue() as $key => $item) {
                $collection->add($this->duplicateFactory->duplicate($item, null, $context));
            }
            $targetValue->setValue($collection);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'map_target' => false,
            'by_reference' => false,
        ]);
    }

    public static function getName(): ?string
    {
        return 'collection';
    }
}
