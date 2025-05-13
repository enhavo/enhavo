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

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;

class CollectionReferenceDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $originalValue = $targetValue->getOriginalValue();

        if (null === $sourceValue->getValue()) {
            $targetValue->setValue(null);
        } elseif (is_array($sourceValue->getValue()) && is_array($originalValue)
            || ($sourceValue->getValue() instanceof Collection && $originalValue instanceof Collection)
        ) {
            foreach ($sourceValue->getValue() as $sourceItem) {
                $exists = false;
                foreach ($originalValue as $originalItem) {
                    if ($sourceItem === $originalItem) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $this->addToCollection($originalValue, $sourceItem);
                }
            }
            foreach ($originalValue as $originalKey => $originalItem) {
                $exists = false;
                foreach ($sourceValue->getValue() as $sourceItem) {
                    if ($sourceItem === $originalItem) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $this->removeFromCollection($originalValue, $originalKey);
                }
            }

            $targetValue->setValue($originalValue);
        } elseif (null === $originalValue) {
            $targetValue->setValue($sourceValue->getValue());
        }
    }

    private function addToCollection(&$collection, $addItem): void
    {
        if ($collection instanceof Collection) {
            $collection->add($addItem);
        } else {
            $collection[] = $addItem;
        }
    }

    private function removeFromCollection(&$collection, $key): void
    {
        if ($collection instanceof Collection) {
            $collection->remove($key);
        } else {
            unset($collection[$key]);
        }
    }

    public static function getName(): ?string
    {
        return 'collection_reference';
    }
}
