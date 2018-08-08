<?php

/**
 * ItemInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Model;

interface ItemTypeInterface
{
    /**
     * @param ItemInterface $item
     * @return void
     */
    public function setItem(ItemInterface $item = null);

    /**
     * @return ItemInterface
     */
    public function getItem();
}