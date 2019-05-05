<?php

namespace Enhavo\Bundle\TaxonomyBundle\Entity;

use Enhavo\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\CollectionInterface;
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
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var CollectionInterface
     */
    protected $taxonomy;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @var string
     */
    protected $slug;

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
     * @return Taxonomy
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
     * Set collection
     *
     * @param CollectionInterface $collection
     * @return Taxonomy
     */
    public function setCollection(CollectionInterface $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return CollectionInterface
     */
    public function getCollection()
    {
        return $this->collection;
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
     *
     * @return Taxonomy
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
     * @return Taxonomy
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
