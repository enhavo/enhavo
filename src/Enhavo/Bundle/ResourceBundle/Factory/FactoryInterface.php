<?php

namespace Enhavo\Bundle\ResourceBundle\Factory;

interface FactoryInterface extends \Sylius\Component\Resource\Factory\FactoryInterface
{
    public function createNew();
}
