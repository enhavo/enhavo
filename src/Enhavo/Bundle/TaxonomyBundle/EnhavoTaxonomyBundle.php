<?php

namespace Enhavo\Bundle\TaxonomyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoTaxonomyBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
