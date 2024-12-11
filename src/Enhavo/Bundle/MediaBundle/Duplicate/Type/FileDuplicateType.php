<?php

namespace Enhavo\Bundle\MediaBundle\Duplicate\Type;

use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

        if ($sourceValue->getValue() === null) {
            $targetValue->setValue(null);
            return;
        }

        $newFile = $this->fileFactory->createFromFile($sourceValue->getValue());
        $targetValue->setValue($newFile);
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
