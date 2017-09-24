<?php
namespace Enhavo\Bundle\MediaBundle\Factory;

use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Finder\SplFileInfo;

class FileFactory extends Factory
{
    use GuessTrait;

    /**
     * @param FileInterface $originalResource
     * @return FileInterface
     */
    public function duplicate(FileInterface $originalResource)
    {
        /** @var FileInterface $file */
        $file = $this->createNew();

        $file->setMimeType($originalResource->getMimeType());
        $file->setExtension($originalResource->getExtension());
        $file->setOrder($originalResource->getOrder());
        $file->setFilename($originalResource->getFilename());
        $file->setParameters($originalResource->getParameters());
        $file->setContent($originalResource->getContent());

        $file->setGarbage(false);
        $file->setGarbageTimestamp(new \DateTime());

        return $file;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return FileInterface
     */
    public function createFromUploadedFile(UploadedFile $uploadedFile)
    {
        /** @var File $newFile */
        $file = $this->createNew();

        $file->setMimeType($uploadedFile->getMimeType());
        $file->setExtension($uploadedFile->guessClientExtension());
        $file->setFilename($uploadedFile->getClientOriginalName());
        $file->setGarbage(true);
        $file->setContent(new PathContent($uploadedFile->getRealPath()));

        return $file;
    }

    /**
     * @param File $file
     * @return FileInterface
     */
    public function createFromFile(File $file)
    {
        return $this->createFromPath($file->getRealPath());
    }

    /**
     * @param SplFileInfo $file
     * @return FileInterface
     */
    public function createFromSplFileInfo(SplFileInfo $file)
    {
        return $this->createFromPath($file->getRealPath());
    }

    /**
     * @param string $path
     * @return FileInterface
     * @throws FileException
     */
    public function createFromPath($path)
    {
        if (!is_readable($path)) {
            throw new FileException(sprintf('File "%s" not found or not readable.', $path));
        }

        $fileInfo = pathinfo($path);

        /** @var FileInterface $file */
        $file = $this->createNew();

        $file->setMimeType($this->guessMimeType($path));
        $file->setExtension(array_key_exists('extension', $fileInfo) ? $fileInfo['extension'] : $this->guessExtension($path));
        $file->setFilename($fileInfo['basename']);
        $file->setGarbage(true);
        $file->setContent(new PathContent($path));

        return $file;
    }
}
