<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.12.18
 * Time: 19:49
 */

namespace Enhavo\Bundle\AppBundle\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;

class Factory implements FactoryInterface
{
    /** @var string */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return new $this->className();
    }
}