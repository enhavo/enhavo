<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CollectionDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    ) {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (null === $sourceValue->getValue()) {
            $targetValue->setValue(null);
        } elseif ($options['by_reference']) {
            $propertyAccessor = new PropertyAccessor();
            $target = $targetValue->getParent();
            $values = [];
            foreach ($sourceValue->getValue() as $key => $item) {
                $values[] = $this->duplicateFactory->duplicate($item, null, $context);
            }
            $propertyAccessor->setValue($target, $targetValue->getPropertyName(), $values);
            $collection = $propertyAccessor->getValue($target, $targetValue->getPropertyName());
            $targetValue->setValue($collection);
        } elseif (is_array($sourceValue->getValue())) {
            $collection = [];
            foreach ($sourceValue->getValue() as $key => $item) {
                $target = null;
                if ($options['map_target']) {
                    $target = is_array($targetValue->getOriginalValue()) ? $targetValue->getOriginalValue()[$key] ?? null : null;
                }
                $collection[$key] = $this->duplicateFactory->duplicate($item, $target, $context);
            }
            $targetValue->setValue($collection);
        } elseif ($sourceValue->getValue() instanceof Collection) {
            $collection = new ArrayCollection();
            foreach ($sourceValue->getValue() as $key => $item) {
                $collection->add($this->duplicateFactory->duplicate($item, null, $context));
            }
            $targetValue->setValue($collection);
        }
    }

    /**
     * Items are compared by their position.
     */
    public function diff($options, SourceValue $oldValue, SourceValue $newValue, Diff $diff, $context): void
    {
        $oldItems = $this->toArray($oldValue->getValue());
        $newItems = $this->toArray($newValue->getValue());

        $count = max(count($oldItems), count($newItems));
        for ($i = 0; $i < $count; ++$i) {
            $diff->enter((string) ($i + 1));
            $diff->diffObject($oldItems[$i] ?? null, $newItems[$i] ?? null, $context);
            $diff->leave();
        }
    }

    private function toArray(mixed $value): array
    {
        if (null === $value) {
            return [];
        }

        $items = [];
        foreach ($value as $item) {
            if (is_object($item)) {
                $items[] = $item;
            }
        }

        return $items;
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
