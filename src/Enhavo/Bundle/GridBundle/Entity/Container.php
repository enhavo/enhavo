<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Container
 */
class Container
{
    /**
     * @var integer
     */
    protected $id;


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
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $configuration;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $columns;

    /**
     * @var \Enhavo\Bundle\GridBundle\Entity\Content
     */
    protected $content;

    /**
     * @var \Enhavo\Bundle\GridBundle\Entity\Column
     */
    protected $column;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->columns = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Container
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set configuration
     *
     * @param string $configuration
     * @return Container
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return string 
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Add columns
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Column $columns
     * @return Container
     */
    public function addColumn(\Enhavo\Bundle\GridBundle\Entity\Column $columns)
    {
        $this->columns[] = $columns;

        return $this;
    }

    /**
     * Remove columns
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Column $columns
     */
    public function removeColumn(\Enhavo\Bundle\GridBundle\Entity\Column $columns)
    {
        $this->columns->removeElement($columns);
    }

    /**
     * Get columns
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Content $content
     * @return Container
     */
    public function setContent(\Enhavo\Bundle\GridBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set column
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Column $column
     * @return Container
     */
    public function setColumn(\Enhavo\Bundle\GridBundle\Entity\Column $column = null)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Column
     */
    public function getColumn()
    {
        return $this->column;
    }
}
