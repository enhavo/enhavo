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
    public function setGrid(GridInterface $grid);

    public function getGrid();
}