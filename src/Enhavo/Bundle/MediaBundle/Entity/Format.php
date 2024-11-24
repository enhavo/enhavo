<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 17:42
 */

namespace Enhavo\Bundle\MediaBundle\Entity;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

class Format implements FormatInterface
{
    private ?int $id = null;
    private string $name;
    private array $parameters = [];
    private string $mimeType;
    private ?string $extension;
    private FileInterface $file;
    private ContentInterface $content;
    private ?\DateTime $lockAt = null;
    private ?string $checksum = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): void
    {
        $this->extension = $extension;
    }

    public function getFilename(): string
    {
        return $this->getFile()->getFilename();
    }

    public function getBasename(): string
    {
        $filename = $this->getFile()->getFilename();
        if ($this->getExtension()) {
            return sprintf('%s.%s', $filename, $this->getExtension());
        }
        return $filename;
    }

    public function getFile(): FileInterface
    {
        return $this->file;
    }

    public function setFile(FileInterface $file): void
    {
        $this->file = $file;
    }

    public function getContent(): ContentInterface
    {
        return $this->content;
    }

    public function setContent(ContentInterface $content): void
    {
        $this->content = $content;
    }

    public function getLockAt(): ?\DateTime
    {
        return $this->lockAt;
    }

    public function setLockAt(?\DateTime $lockAt): void
    {
        $this->lockAt = $lockAt;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function setChecksum(string $checksum): void
    {
        $this->checksum = $checksum;
    }
}
