<?php

namespace Enhavo\Bundle\AppBundle\Type;

/**
 * @author gseidel
 */
interface CollectorInterface
{
    public function add($alias, $id);

    public function getType($name);

    public function getTypes();
}
