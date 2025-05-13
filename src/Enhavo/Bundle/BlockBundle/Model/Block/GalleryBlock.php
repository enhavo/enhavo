<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Gallery
 */
class GalleryBlock extends AbstractBlock
{
    private ?string $title = null;
    private ?string $text = null;

    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return GalleryBlock
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return GalleryBlock
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add files
     *
     * @return GalleryBlock
     */
    public function addFiles(FileInterface $file)
    {
        if (null === $this->files) {
            $this->files = new ArrayCollection();
        }

        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove files
     */
    public function removeFiles(FileInterface $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add file
     *
     * @return GalleryBlock
     */
    public function addFile(FileInterface $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     */
    public function removeFile(FileInterface $file)
    {
        $this->files->removeElement($file);
    }
}
