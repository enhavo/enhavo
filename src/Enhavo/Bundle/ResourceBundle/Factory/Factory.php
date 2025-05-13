<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Factory;

class Factory implements FactoryInterface
{
    public function __construct(
        private readonly string $className,
    ) {
    }

    public function createNew()
    {
        return new $this->className();
    }
}
