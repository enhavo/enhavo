<?php

/**
 * ItemInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Model;

interface ItemInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @param GridInterface $grid
     * @return mixed
     */
    public function setGrid(GridInterface $grid);

    /**
     * @return GridInterface
     */
    public function getGrid();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return ItemTypeInterface
     */
    public function getItemType();

    /**
     * @param ItemTypeInterface $item
     */
    public function setItemType(ItemTypeInterface $item = null);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $position
     */
    public function setPosition($position);
}