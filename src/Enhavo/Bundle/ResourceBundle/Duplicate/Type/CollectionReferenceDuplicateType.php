<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Type;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionReferenceDuplicateType extends AbstractDuplicateType
{
    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (!$this->isGroupSelected($options, $context)) {
            return;
        }

        $originalValue = $targetValue->getOriginalValue();

        if ($sourceValue->getValue() === null) {
            $targetValue->setValue(null);
        } else if (is_array($sourceValue->getValue()) && is_array($originalValue)
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
        } else if ($originalValue === null) {
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'groups' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'collection_reference';
    }
}
