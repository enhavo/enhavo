<?php
/**
 * MediaManager.php
 *
 * @since 13/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\FileNotFound\FileNotFoundHandlerInterface;
use Enhavo\Bundle\MediaBundle\Model\FileContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;

class MediaManager
{
    public function __construct(
        private readonly FormatManager $formatManager,
        private readonly FileRepository $fileRepository,
        private readonly AssociationFinder $associationFinder,
        private ResourceManager $resourceManager,
        private readonly FileNotFoundHandlerInterface $fileNotFoundHandler,
        private array $fileNotFoundHandlerParameter,
    ) {
    }

    public function getFormat(FileInterface $file, string $format): FormatInterface
    {
        return $this->formatManager->getFormat($file, $format);
    }

    public function collectGarbage(): void
    {
        $files = $this->fileRepository->findGarbage();
        foreach ($files as $file) {
            $this->resourceManager->delete($file);
        }
    }

    public function handleFileNotFound(FileContentInterface $file): void
    {
        if ($file instanceof FileInterface) {
            $this->fileNotFoundHandler->handleFileNotFound($file, $this->fileNotFoundHandlerParameter);
        } else if ($file instanceof FormatInterface) {
            $formatName = $file->getName();
            $originalFile = $file->getFile();
            if (!file_exists($originalFile->getContent()->getFilePath()))  {
                $this->fileNotFoundHandler->handleFileNotFound($originalFile, $this->fileNotFoundHandlerParameter);
            }
            $this->formatManager->applyFormat($originalFile, $formatName);
        }
    }

    /**
     * Finds entities referencing the file
     */
    public function findReferencesTo(FileInterface $file): array
    {
        return $this->associationFinder->findAssociationsTo($file, FileInterface::class, [Format::class]);
    }

    public function getMaxUploadSize(): int
    {
        static $maxSize = -1;

        if ($maxSize < 0) {
            // Start with postMaxSize.
            $postMaxSize = $this->parseSize(ini_get('post_max_size'));
            if ($postMaxSize > 0) {
                $maxSize = $postMaxSize;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $uploadMax = $this->parseSize(ini_get('upload_max_filesize'));
            if ($uploadMax > 0 && $uploadMax < $maxSize) {
                $maxSize = $uploadMax;
            }
        }

        return $maxSize;
    }

    private function parseSize($size): float
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }
}
