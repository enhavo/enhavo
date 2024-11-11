<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 13:29
 */

namespace Enhavo\Bundle\MediaBundle\Factory;

use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FormatFactory extends Factory
{
    use GuessTrait;

    public function __construct(
        string $className,
        private readonly ChecksumGeneratorInterface $checksumGenerator,
    )
    {
        parent::__construct($className);
    }

    public function createFromUploadedFile(string $name, FileInterface $file, UploadedFile $uploadedFile): FormatInterface
    {
        /** @var FormatInterface $newFile */
        $format = $this->createNew();

        $format->setMimeType($uploadedFile->getMimeType());
        $format->setExtension($uploadedFile->guessClientExtension());
        $format->setContent(new PathContent($uploadedFile->getRealPath()));

        $this->updateFile($name, $file, $format);

        return $format;
    }

    public function createFromPath(string $name, FileInterface $file, string $path): FormatInterface
    {
        if (!is_readable($path)) {
            throw new FileException(sprintf('File "%s" not found or not readable.', $path));
        }

        $fileInfo = pathinfo($path);

        /** @var FormatInterface $format */
        $format = $this->createNew();

        $format->setMimeType($this->guessMimeType($path));
        $format->setExtension($fileInfo['extension'] ?? null);
        $format->setContent(new PathContent($path));

        $this->updateFile($name, $file, $format);

        return $format;
    }

    public function createFromMediaFile(string $name, FileInterface $file): FormatInterface
    {
        /** @var FormatInterface $format */
        $format = $this->createNew();

        $format->setMimeType($file->getMimeType());
        $format->setExtension($file->getExtension());
        $format->setParameters($file->getParameters());
        $format->setFile($file);

        $content = new Content($file->getContent()->getContent());
        $format->setContent($content);

        $this->updateFile($name, $file, $format);

        return $format;
    }

    private function updateFile(string $name, FileInterface $file, FormatInterface $format): void
    {
        $format->setChecksum($this->checksumGenerator->getChecksum($format->getContent()));
        $format->setName($name);
        $format->setFile($file);
    }
}
