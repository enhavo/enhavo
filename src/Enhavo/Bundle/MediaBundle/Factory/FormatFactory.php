<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 13:29
 */

namespace Enhavo\Bundle\MediaBundle\Factory;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FormatFactory extends Factory
{
    use GuessTrait;

    /**
     * @param UploadedFile $uploadedFile
     * @return FormatInterface
     */
    public function createFromUploadedFile(UploadedFile $uploadedFile)
    {
        /** @var FormatInterface $newFile */
        $format = $this->createNew();

        $format->setMimeType($uploadedFile->getMimeType());
        $format->setExtension($uploadedFile->guessClientExtension());
        $format->setContent(new PathContent($uploadedFile->getRealPath()));

        return $format;
    }

    /**
     * @param File $file
     * @return FormatInterface
     */
    public function createFromFile(File $file)
    {
        return $this->createFromPath($file->getRealPath());
    }

    /**
     * @param SplFileInfo $file
     * @return FormatInterface
     */
    public function createFromSplFileInfo(SplFileInfo $file)
    {
        return $this->createFromPath($file->getRealPath());
    }

    /**
     * @param string $path
     * @return FormatInterface
     * @throws FileException
     */
    public function createFromPath($path)
    {
        if (!is_readable($path)) {
            throw new FileException(sprintf('File "%s" not found or not readable.', $path));
        }

        $fileInfo = pathinfo($path);

        /** @var FormatInterface $format */
        $format = $this->createNew();

        $format->setMimeType($this->guessMimeType($path));
        $format->setExtension(array_key_exists('extension', $fileInfo) ? $fileInfo['extension'] : $this->guessExtension($path));
        $format->setContent(new PathContent($path));

        return $format;
    }

    public function createFromMediaFile(FileInterface $file)
    {
        /** @var FormatInterface $format */
        $format = $this->createNew();

        $format->setMimeType($file->getMimeType());
        $format->setExtension($file->getExtension());
        $format->setParameters($file->getParameters());
        $format->setFile($file);

        $content = new Content($file->getContent()->getContent());
        $format->setContent($content);

        return $format;
    }
}
