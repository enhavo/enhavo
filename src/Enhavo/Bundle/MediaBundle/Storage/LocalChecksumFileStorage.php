<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 14:07
 */

namespace Enhavo\Bundle\MediaBundle\Storage;

use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
use Enhavo\Bundle\MediaBundle\Repository\FormatRepository;
use Symfony\Component\Filesystem\Filesystem;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

class LocalChecksumFileStorage implements StorageInterface, StorageChecksumInterface
{
    public function __construct(
        private readonly string $basePath,
        private readonly Filesystem $filesystem,
        private readonly FileRepository $fileRepository,
        private readonly FormatRepository $formatRepository,
    )
    {
    }

    public function deleteContent(FormatInterface|FileInterface $file): void
    {
        $this->checkFile($file);

        if ($file instanceof FileInterface) {
            $amount = $this->fileRepository->countByChecksum($file->getChecksum());
        } else if ($file instanceof FormatInterface) {
            $amount = $this->formatRepository->countByChecksum($file->getChecksum());
        }

        if ($amount === 0) {
            $path = $this->getFilePath($file);
            if (!$this->filesystem->exists($path)) {
                throw new FileNotFoundException(sprintf(
                    'File not found for name "%s". Expected on path "%s"', $file->getBasename(), $path
                ));
            }
            $this->filesystem->remove($path);
        }
    }

    public function saveContent(FormatInterface|FileInterface $file): ContentInterface
    {
        $this->checkFile($file);
        $dir = $this->getDirPath($file);
        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->mkdir($dir, 0755);
        }

        $path = $this->getFilePath($file);

        if ($file instanceof FileInterface) {
            if ($this->filesystem->exists($path)) {
                return $file->getContent();
            }
        }

        $this->filesystem->dumpFile($path, $file->getContent()->getContent());
        return new PathContent($path);
    }

    public function getContent(FormatInterface|FileInterface $file): ContentInterface
    {
        $this->checkFile($file);
        $path = $this->getFilePath($file);
        if (!$this->filesystem->exists($path)) {
            throw new FileNotFoundException(sprintf(
                'File not found for name "%s". Expected on path "%s"', $file->getBasename(), $path
            ));
        }

        return new PathContent($path);
    }

    public function existsChecksum(string $checksum): bool
    {
        $path = $this->getPathByChecksum($checksum);
        return $this->filesystem->exists($path);
    }

    public function getContentByChecksum(string $checksum): ContentInterface
    {
        $path = $this->getPathByChecksum($checksum);
        if (!$this->filesystem->exists($path)) {
            throw new StorageException(sprintf(
                'File not found for checksum "%s"', $checksum
            ));
        }

        return new PathContent($path);
    }

    private function getPathByChecksum(string $checksum): string
    {
        return sprintf(
            '%s/file/%s/%',
            $this->basePath,
            substr($checksum, 0, 2),
            substr($checksum, 2),
        );
    }

    private function checkFile(FormatInterface|FileInterface $file): void
    {
        if ($file->getChecksum() === null) {
            throw new StorageException(sprintf(
                'File with name "%s" need checksum for storing to filesystem.', $file->getBasename()
            ));
        }
    }

    private function getFilePath(FormatInterface|FileInterface $file): string
    {
        return $this->getDirPath($file) . '/' . $this->getFilename($file);
    }

    private function getDirPath(FormatInterface|FileInterface $file): string
    {
        if ($file instanceof FileInterface) {
            return sprintf(
                '%s/file/%s',
                $this->basePath,
                substr($file->getChecksum(), 0, 2),
            );
        } else {
            return sprintf(
                '%s/format/%s',
                $this->basePath,
                substr($file->getChecksum(), 0, 2),
            );
        }
    }

    private function getFilename(FormatInterface|FileInterface $file): string
    {
        if ($file instanceof FileInterface) {
            return substr($file->getChecksum(), 2);
        } else {
            return substr($file->getChecksum(), 2);
        }
    }
}
