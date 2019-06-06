<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 17.08.18
 * Time: 01:30
 */

namespace Enhavo\Bundle\GridBundle\Model;


interface GridsAwareInterface
{
    /**
     * Get items
     *
     * @return GridInterface[]
     */
    public function getGrids();
}