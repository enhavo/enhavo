<?php

namespace Enhavo\Bundle\MediaBundle\Duplicate\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;

class FileDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    )
    {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $source = $sourceValue->getValue();

        if ($source === null) {
            $targetValue->setValue(null);
        } else if ($source instanceof Collection) {
            $collection = new ArrayCollection();
            foreach ($source as $file) {
                $newFile = $this->duplicateFactory->duplicate($file, null, $context);
                $collection->add($newFile);
            }
            $targetValue->setValue($collection);
        } else {
            $newFile = $this->duplicateFactory->duplicate($source, null, $context);
            $targetValue->setValue($newFile);
        }
    }

    public static function getName(): ?string
    {
        return 'file';
    }
}
