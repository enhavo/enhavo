<?php

namespace Enhavo\Bundle\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Taxonomy
 */
class Term implements TaxonomyInterface, ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Taxonomy
     */
    private $taxonomy;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $text;

    /**
     * @var FileInterface
     */
    private $picture;

    /**
     * @var Term[]|Collection
     */
    private $children;

    /**
     * @var Term
     */
    private $parent;

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
     * @return Term
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

    public function __toString()
    {
        if($this->name) {
            return $this->name;
        }
        return '';
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Term
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
     * @param FileInterface|null $picture
     *
     * @return Term
     */
    public function setPicture(FileInterface $picture = null)
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * Get picture
     *
     * @return FileInterface|null
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
