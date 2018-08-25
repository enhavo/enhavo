<?php

namespace Enhavo\Bundle\SearchBundle\Model\Database;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * DataSet
 */
class DataSet
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $contentClass;

    /**
     * @var object
     */
    private $content;

    /**
     * @var integer
     */
    private $contentId;

    /**
     * @var ArrayCollection
     */
    private $indexes;

    public function __construct()
    {
        $this->indexes = new ArrayCollection();
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
     * @return string
     */
    public function getContentClass()
    {
        return $this->contentClass;
    }

    /**
     * @param string $contentClass
     */
    public function setContentClass($contentClass)
    {
        $this->contentClass = $contentClass;
    }

    /**
     * @return object
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param object $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param int $contentId
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;
    }

    /**
     * @param mixed $index
     */
    public function addIndex($index)
    {
        $index->setDataset($this);
        $this->indexes->add($index);
    }

    /**
     * @param mixed $index
     */
    public function removeIndex(Index $index)
    {
        $index->setDataset(null);
        $this->indexes->removeElement($index);
    }

    /**
     * @return ArrayCollection
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    public function resetIndex()
    {
        /** @var Index $index */
        foreach($this->indexes as $index) {
            $index->setDataSet(null);
        }
        $this->indexes->clear();
    }
}
