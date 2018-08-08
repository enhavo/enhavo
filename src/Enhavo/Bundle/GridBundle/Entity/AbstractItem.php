<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08.08.18
 * Time: 22:26
 */

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Model\ItemInterface;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class AbstractItem implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var ItemInterface
     */
    protected $item;

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
     * @return ItemInterface
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param ItemInterface $item
     */
    public function setItem(ItemInterface $item = null)
    {
        $this->item = $item;
    }
}