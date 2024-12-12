<?php

namespace Enhavo\Bundle\MediaBundle\Duplicate\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Collection;

class FileDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly FileFactory $fileFactory,
    )
    {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        if (!$this->isGroupSelected($options, $context)) {
            return;
        }

        $source = $sourceValue->getValue();

        if ($source === null) {
            $targetValue->setValue(null);
        } else if ($source instanceof Collection) {
            $collection = new ArrayCollection();
            foreach ($source as $file) {
                $newFile = $this->fileFactory->createFromFile($file);
                $collection->add($newFile);
            }
            $targetValue->setValue($collection);
        } else {
            $newFile = $this->fileFactory->createFromFile($source);
            $targetValue->setValue($newFile);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'groups' => null
        ]);
    }

    public static function getName(): ?string
    {
        return 'file';
    }
}
