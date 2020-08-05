<?php


namespace Enhavo\Bundle\DashboardBundle\Provider;


use Enhavo\Component\Type\TypeInterface;

interface ProviderTypeInterface extends TypeInterface
{
    public function getData($options);
}
