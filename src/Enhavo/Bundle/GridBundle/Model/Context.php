<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 17.08.18
 * Time: 01:29
 */

namespace Enhavo\Bundle\GridBundle\Model;


class Context
{
    /**
     * @var Context
     */
    private $parent;

    /**
     * @var Context
     */
    private $before;

    /**
     * @var Context
     */
    private $next;

    /**
     * @var ItemTypeInterface|ItemAwareInterface
     */
    private $data;

    /**
     * @return Context
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Context $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Context
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @param Context $before
     */
    public function setBefore($before)
    {
        $this->before = $before;
    }

    /**
     * @return Context
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param Context $next
     */
    public function setNext($next)
    {
        $this->next = $next;
    }

    /**
     * @return ItemAwareInterface|ItemInterface
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param ItemAwareInterface|ItemInterface $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}