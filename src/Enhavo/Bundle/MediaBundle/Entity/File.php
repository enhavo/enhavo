<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class File implements FileInterface
{
    private ?int $id = null;
    private ?string $mimeType = null;
    private ?string $extension = null;
    private ?int $order = null;
    private ?string $filename = null;
    private array $parameters = [];
    private bool $garbage = false;
    private ?\DateTime $garbageTimestamp;
    private ?ContentInterface $content = null;
    private ?string $token = null;
    private ?string $checksum = null;
    private ?\DateTime $createdAt = null;
    private ?\DateTime $garbageCheckedAt = null;
    private Collection $formats;

    public function __construct()
    {
        $this->formats = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setMimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setExtension(?string $extension): void
    {
        $this->extension = $extension;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function setOrder(?int $order): void
    {
        $this->order = $order;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function setBasename(string $basename): void
    {
        $info = pathinfo($basename);
        $this->setFilename($info['filename']);

        $this->setExtension($info['extension'] ?? null);
    }

    public function getBasename(): string
    {
        return $this->extension ? $this->filename.'.'.$this->extension : $this->filename;
    }

    public function setParameter(string $key, mixed $value): void
    {
        if (!$this->parameters) {
            $this->parameters = [];
        }

        $this->parameters[$key] = $value;
    }

    public function getParameter(string $key): mixed
    {
        if (!$this->parameters) {
            $this->parameters = [];
        }

        return $this->parameters[$key] ?? null;
    }

    public function getParameters(): array
    {
        if (!$this->parameters) {
            $this->parameters = [];
        }

        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function isGarbage(): bool
    {
        return $this->garbage;
    }

    public function setGarbage(bool $garbage, ?\DateTime $garbageTimestamp = null): void
    {
        $this->garbage = $garbage;

        if (null == $garbageTimestamp) {
            $garbageTimestamp = new \DateTime();
        }
        $this->setGarbageTimestamp($garbageTimestamp);
    }

    public function getGarbageTimestamp(): ?\DateTime
    {
        return $this->garbageTimestamp;
    }

    public function setGarbageTimestamp(?\DateTime $garbageTimestamp): void
    {
        $this->garbageTimestamp = $garbageTimestamp;
    }

    public function isImage(): bool
    {
        return 'image' == strtolower(substr($this->getMimeType(), 0, 5));
    }

    public function getContent(): ContentInterface
    {
        return $this->content;
    }

    public function setContent(ContentInterface $content): void
    {
        $this->content = $content;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function setChecksum(string $checksum): void
    {
        $this->checksum = $checksum;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getGarbageCheckedAt(): ?\DateTime
    {
        return $this->garbageCheckedAt;
    }

    public function setGarbageCheckedAt(?\DateTime $garbageCheckedAt): void
    {
        $this->garbageCheckedAt = $garbageCheckedAt;
    }

    public function getFormats(): ArrayCollection|Collection
    {
        return $this->formats;
    }

    public function addFormat(Format $format): void
    {
        $this->formats->add($format);
        $format->setFile($this);
    }

    public function removeFormat(Format $format): void
    {
        $this->formats->removeElement($format);
        $format->setFile(null);
    }

    public function getShortChecksum(): string
    {
        return substr($this->checksum, 0, 8);
    }
}
