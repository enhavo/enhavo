<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
