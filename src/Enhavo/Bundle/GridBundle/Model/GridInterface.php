<?php

/**
 * GridInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Model;

interface GridInterface extends ItemsAwareInterface
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
}