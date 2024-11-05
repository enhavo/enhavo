<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;

interface ItemInterface
{
    public function getId(): ?int;
    public function getFile(): FileInterface;
    public function setFile(FileInterface $file): void;
    public function getContentType(): ?string;
    public function setContentType(?string $contentType): void;
    /** @return Collection<TermInterface> */
    public function getTags(): Collection;
    public function addTag(TermInterface $tag): void;
    public function removeTag(TermInterface $tag): void;
    /** @return Collection<LibraryFileInterface> */
    public function getUsedFiles(): Collection;
    public function addUsedFile(LibraryFileInterface $usedFile);
    public function removeUsedFile(LibraryFileInterface $usedFile);
}
