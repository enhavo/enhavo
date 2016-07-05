<?php

namespace Enhavo\Bundle\SearchBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoSearchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_search.index_collector', 'enhavo_search.index')
        );
    }
}
