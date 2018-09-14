<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 17.08.18
 * Time: 01:30
 */

namespace Enhavo\Bundle\GridBundle\Model;


interface ItemsAwareInterface
{
    /**
     * AddItem
     *
     * @param ItemInterface $item
     */
    public function addItem(ItemInterface $item);

    /**
     * Remove items
     *
     * @param ItemInterface $item
     */
    public function removeItem(ItemInterface $item);

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems();
}