<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Duplicate\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;

class FileDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly DuplicateFactory $duplicateFactory,
    ) {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $source = $sourceValue->getValue();

        if (null === $source) {
            $targetValue->setValue(null);
        } elseif ($source instanceof Collection) {
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

    /**
     * Files are compared by their checksum, because a revision holds a copy of the file with another id.
     */
    public function diff($options, SourceValue $oldValue, SourceValue $newValue, Diff $diff, $context): void
    {
        $this->diffFiles($oldValue->getValue(), $newValue->getValue(), $diff);
    }

    private function diffFiles(mixed $old, mixed $new, Diff $diff): void
    {
        $oldFiles = $this->toArray($old);
        $newFiles = $this->toArray($new);

        $oldChecksums = array_map(fn (FileInterface $file) => $file->getChecksum(), $oldFiles);
        $newChecksums = array_map(fn (FileInterface $file) => $file->getChecksum(), $newFiles);
        if ($oldChecksums === $newChecksums) {
            return;
        }

        $diff->addChange($this->getFilenames($oldFiles), $this->getFilenames($newFiles));
    }

    /**
     * @return FileInterface[]
     */
    private function toArray(mixed $value): array
    {
        if ($value instanceof FileInterface) {
            return [$value];
        }

        $files = [];
        foreach ($value ?? [] as $file) {
            if ($file instanceof FileInterface) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * @param FileInterface[] $files
     */
    private function getFilenames(array $files): ?string
    {
        $filenames = array_map(fn (FileInterface $file) => $file->getFilename(), $files);

        return $filenames ? implode(', ', $filenames) : null;
    }

    public static function getName(): ?string
    {
        return 'file';
    }
}
