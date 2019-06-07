<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 17.08.18
 * Time: 01:30
 */

namespace Enhavo\Bundle\BlockBundle\Model;


interface ContainerAwareInterface
{
    /**
     * Get containers
     *
     * @return ContainerInterface[]
     */
    public function getContainers();
}