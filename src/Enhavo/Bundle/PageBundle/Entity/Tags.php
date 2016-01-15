<?php

namespace Enhavo\Bundle\PageBundle\Entity;

/**
 * Tags
 */
class Tags
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;


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
     * Set title
     *
     * @param string $title
     *
     * @return Tags
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
     * @var string
     */
    private $order;

    /**
     * Set order
     *
     * @param string $order
     *
     * @return Tags
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @var \Enhavo\Bundle\PageBundle\Entity\Page
     */
    private $page;


    /**
     * Set page
     *
     * @param \Enhavo\Bundle\PageBundle\Entity\Page $page
     *
     * @return Tags
     */
    public function setPage(\Enhavo\Bundle\PageBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Enhavo\Bundle\PageBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
    /**
     * @var boolean
     */
    private $public;


    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return Tags
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
}
