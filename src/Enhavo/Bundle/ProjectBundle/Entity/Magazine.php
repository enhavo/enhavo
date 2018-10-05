<?php

namespace Enhavo\Bundle\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Magazine
 */
class Magazine implements ResourceInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $toc;

    /**
     * @var string
     */
    private $condition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pictures;

    /**
     * @var FileInterface
     */
    private $cover;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->toc = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Magazine
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Add toc
     *
     * @param \Enhavo\Bundle\ProjectBundle\Entity\Content $toc
     * @return Magazine
     */
    public function addToc(\Enhavo\Bundle\ProjectBundle\Entity\Content $toc)
    {
        $toc->setMagazine($this);
        $this->toc[] = $toc;

        return $this;
    }

    /**
     * Remove toc
     *
     * @param \Enhavo\Bundle\ProjectBundle\Entity\Content $toc
     */
    public function removeToc(\Enhavo\Bundle\ProjectBundle\Entity\Content $toc)
    {
        $toc->setMagazine(null);
        $this->toc->removeElement($toc);
    }

    /**
     * Get toc
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getToc()
    {
        return $this->toc;
    }

    /**
     * Set condition
     *
     * @param string $condition
     * @return Magazine
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string 
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Add pictures
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $pictures
     * @return Magazine
     */
    public function addPicture(\Enhavo\Bundle\MediaBundle\Entity\File $pictures)
    {
        $this->pictures[] = $pictures;

        return $this;
    }

    /**
     * Remove pictures
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $pictures
     */
    public function removePicture(\Enhavo\Bundle\MediaBundle\Entity\File $pictures)
    {
        $this->pictures->removeElement($pictures);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @return FileInterface
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param FileInterface $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }
}
