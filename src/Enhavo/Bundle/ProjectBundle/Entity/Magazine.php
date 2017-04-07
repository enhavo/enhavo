<?php

namespace Enhavo\Bundle\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
}
