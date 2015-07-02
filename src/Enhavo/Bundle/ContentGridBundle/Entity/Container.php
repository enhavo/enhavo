<?php

namespace enhavo\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Container
 */
class Container
{
    /**
     * @var integer
     */
    private $id;


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
    private $type;

    /**
     * @var string
     */
    private $configuration;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $columns;

    /**
     * @var \enhavo\ContentBundle\Entity\Content
     */
    private $content;

    /**
     * @var \enhavo\ContentBundle\Entity\Column
     */
    private $column;

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
     * @param \enhavo\ContentBundle\Entity\Column $columns
     * @return Container
     */
    public function addColumn(\enhavo\ContentBundle\Entity\Column $columns)
    {
        $this->columns[] = $columns;

        return $this;
    }

    /**
     * Remove columns
     *
     * @param \enhavo\ContentBundle\Entity\Column $columns
     */
    public function removeColumn(\enhavo\ContentBundle\Entity\Column $columns)
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
     * @param \enhavo\ContentBundle\Entity\Content $content
     * @return Container
     */
    public function setContent(\enhavo\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \enhavo\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set column
     *
     * @param \enhavo\ContentBundle\Entity\Column $column
     * @return Container
     */
    public function setColumn(\enhavo\ContentBundle\Entity\Column $column = null)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column
     *
     * @return \enhavo\ContentBundle\Entity\Column
     */
    public function getColumn()
    {
        return $this->column;
    }
}
