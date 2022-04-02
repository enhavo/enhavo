<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;

class File extends \Enhavo\Bundle\MediaBundle\Entity\File
{
    private ?string $contentType;
    private Collection $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    /**
     * @param string|null $contentType
     */
    public function setContentType(?string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @param TermInterface $tag
     * @return void
     */
    public function addTag(TermInterface $tag): void
    {
        $this->tags[] = $tag;
    }

    /**
     * @param TermInterface $tag
     * @return void
     */
    public function removeTag(TermInterface $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return ArrayCollection|Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
