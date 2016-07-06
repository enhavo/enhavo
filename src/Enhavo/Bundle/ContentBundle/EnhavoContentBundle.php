<?php

namespace Enhavo\Bundle\ContentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;

class EnhavoContentBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_content.sitemap_collector', 'enhavo.sitemap_collector')
        );
    }
}
