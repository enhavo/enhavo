<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.12.18
 * Time: 19:49
 */

namespace Enhavo\Bundle\ResourceBundle\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;

class Factory implements FactoryInterface
{
    public function __construct(
        private string $className
    )
    {
    }

    public function createNew()
    {
        return new $this->className();
    }
}
