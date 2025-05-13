<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;

class Item implements ItemInterface
{
    private ?int $id = null;
    private FileInterface $file;
    private ?string $contentType = null;
    /** @var Collection<TermInterface> */
    private Collection $tags;
    /** @var Collection<LibraryFileInterface> */
    private Collection $usedFiles;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->usedFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): FileInterface
    {
        return $this->file;
    }

    public function setFile(FileInterface $file): void
    {
        $this->file = $file;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function addTag(TermInterface $tag): void
    {
        $this->tags[] = $tag;
    }

    public function removeTag(TermInterface $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getUsedFiles(): Collection
    {
        return $this->usedFiles;
    }

    public function addUsedFile(LibraryFileInterface $usedFile)
    {
        $this->usedFiles->add($usedFile);
        $usedFile->setItem($this);
    }

    public function removeUsedFile(LibraryFileInterface $usedFile)
    {
        $this->usedFiles->removeElement($usedFile);
        $usedFile->setItem(null);
    }
}
