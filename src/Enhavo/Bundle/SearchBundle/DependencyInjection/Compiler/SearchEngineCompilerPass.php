<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-23
 * Time: 18:52
 */

namespace Enhavo\Bundle\SearchBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SearchEngineCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->addAliases([
            'enhavo_search.search.engine' => $container->getParameter('enhavo_search.search.engine'),
            EngineInterface::class => $container->getParameter('enhavo_search.search.engine'),
        ]);
    }
}
